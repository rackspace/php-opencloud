<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Resource;

use Guzzle\Http\EntityBody;
use Guzzle\Http\Url;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;

/**
 * Objects are the basic storage entities in Cloud Files. They represent the 
 * files and their optional metadata you upload to the system. When you upload 
 * objects to Cloud Files, the data is stored as-is (without compression or 
 * encryption) and consists of a location (container), the object's name, and 
 * any metadata you assign consisting of key/value pairs.
 */
class DataObject extends AbstractResource
{
    const HEADER_METADATA_PREFIX = 'X-Object-Meta-';
    const HEADER_METADATA_UNSET_PREFIX = 'X-Remove-Object-Meta-';

    private $name;
    
    /**
     * @var EntityBody 
     */
    private $content;
    
    private $directory = false;
    
    public function __construct($container, $cdata = null)
    {
        parent::__construct();

        $this->container = $container;
   
        // For pseudo-directories, we need to ensure the name is set
        if (!empty($cdata->subdir)) {
            $this->name = $cdata->subdir;
            $this->directory = true;
            return;
        } 
        
        $this->populate($cdata);
    }
    
    /**
     * @return bool Is this data object a pseudo-directory?
     */
    public function isDirectory()
    {
        return (bool) $this->directory;
    }
    
    public function setContent($content)
    {
        $this->content = EntityBody::factory($content);
        return $this;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function getContentType()
    {
        if (!$this->content) {
            return false;
        }
        return $this->content->getContentType();
    }
    
    public function getContentLength()
    {
        if (!$this->content) {
            return false;
        }
        return $this->content->getContentLength();
    }
    
    public function getEtag()
    {
        if (!$this->content) {
            return false;
        }
        return $this->content->getContentMd5();
    }
    
    public function primaryKeyField()
    {
        return 'name';
    }
    
    public function getUrl($path = null, array $params = array())
    {
        if (!$this->name) {
            throw new Exceptions\NoNameError(Lang::translate('Object has no name'));
        }

        return $this->container->getUrl(urlencode($this->name));
    }

    public function update($params = array())
    {
        return $this->container->uploadObject(array(
            'name' => $this->name,
            'body' => $this->content,
            'metadata' => $this->metadata->toArray()
        ));
    }

    public function copy($target)
    {
        if (is_string($target)) {
            $destination = $target;
        } elseif ($target instanceof DataObject) {
            $destination = sprintf('/%s/%s', 
                $target->getContainer()->getName(), $target->getName());
        } else {
            throw new Exceptions\InvalidArgumentError(sprintf(
                'The target must either be a string representation of a '
                . 'destination /<container>/<object> or an instance of '
                . 'DataObject. You passed in [%s]',
                print_r($target, true)
            ));
        }
        
        $request = $this->getService()
            ->getClient()
            ->createRequest('COPY', $this->getUrl(), array(
                'Destination' => (string) $destination
            ))
            ->setExpectedResponse(202);
        
        return $request->send();
    }
    
    public function delete($params = array())
    {
        $this->populate($params);
        return $this->getService()->getClient()->delete($this->getUrl())->send();
    }

    public function getTemporaryUrl($expires, $method)
    {
        $method = strtoupper($method);
        $expiry = time() + (int) $expires;

        // check for proper method
        if ($method != 'GET' && $method != 'PUT') {
            throw new Exceptions\InvalidArgumentError(sprintf(
                'Bad method [%s] for TempUrl; only GET or PUT supported',
                $method
            ));
        }

        $url  = $this->getUrl();
        $body = sprintf("%s\n%d\n%s", $method, $expiry, $url->getPath());
        $hash = hash_hmac('sha1', $body, $this->getService()->getTempUrlSecret());

        return sprintf('%s?temp_url_sig=%s&temp_url_expires=%d', $url, $hash, $expiry);
    }

    public function purge($email)
    {
        $cdn = $this->getContainer()->getCdn();

        $url = clone $cdn->getUrl();
        $url->addPath($this->name);
        
        return $this->getService()
            ->getClient()
            ->delete($url, array('X-Purge-Email' => $email))
            ->send();
    }

    public function getPublicUrl($type = UrlType::CDN)
    {
        $cdn = $this->container->getCdn();
        
        switch (strtoupper($type)) {
            case UrlType::CDN:
                $uri = $cdn->getCdnUri();
                break;
            case UrlType::SSL:
                $uri = $cdn->getCdnSslUri();
                break;
            case UrlType::STREAMING:
                $uri = $cdn->getCdnStreamingUri();
                break;
            case UrlType::IOS_STREAMING:
            	$uri = $cdn->getIosStreamingUri();
                break; 
        }
        
        return (isset($uri)) ? Url::factory($uri)->addPath($this->name) : false;
    }

    public function refresh()
    {
        if (!$this->name) {
            throw new Exceptions\NoNameError(Lang::translate('Cannot retrieve an unnamed object'));
        }

        $response = $this->getService()->getClient()
            ->get($this->getUrl())
            ->send();

        $this->content = $response->getBody();
        $this->stockFromHeaders($response->getHeaders());
    }

}