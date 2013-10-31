<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Resource;

use Guzzle\Http\EntityBody;
use Guzzle\Http\Url;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\ObjectStore\Constants\UrlType;

/**
 * Objects are the basic storage entities in Cloud Files. They represent the 
 * files and their optional metadata you upload to the system. When you upload 
 * objects to Cloud Files, the data is stored as-is (without compression or 
 * encryption) and consists of a location (container), the object's name, and 
 * any metadata you assign consisting of key/value pairs.
 */
class DataObject extends AbstractResource
{
    const METADATA_LABEL = 'Object';

    private $container;
    
    protected $name;
    
    /**
     * @var EntityBody 
     */
    protected $content;
    
    protected $directory = false;
    
    protected $contentType;
    
    public function __construct(Container $container, $data = null)
    {
        $this->setContainer($container);
        $this->setService($container->getService());
        
        // For pseudo-directories, we need to ensure the name is set
        if (!empty($data->subdir)) {
            $this->setName($data->subdir)->setDirectory(true);
            return;
        }
        
        $this->populate($data);
    }
    
    public function setContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }
    
    public function getContainer()
    {
        return $this->container;
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
    
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }
    
    public function getContentType()
    {
        return $this->contentType ?: $this->content->getContentType();
    }
    
    public function getContentLength()
    {
        return (!$this->content) ? 0 : $this->content->getContentLength();
    }
    
    public function getEtag()
    {
        return (!$this->content) ? null : $this->content->getContentMd5();
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

        return $this->container->getUrl($this->name);
    }

    public function update($params = array())
    {
        return $this->container->uploadObject($this->name, $this->content, $this->metadata->toArray());
    }

    public function copy($destination)
    {
        return $this->getService()
            ->getClient()
            ->createRequest('COPY', $this->getUrl(), array(
                'Destination' => (string) $destination
            ))
            ->send();
    }
    
    public function delete($params = array())
    {
        return $this->getService()->getClient()->delete($this->getUrl())->send();
    }

    /**
     * @link http://docs.rackspace.com/files/api/v1/cf-devguide/content/TempURL-d1a4450.html
     *
     * @param $expires Expiration time in seconds
     * @param $method  What method can use this URL? (`GET' or `PUT')
     * @return string
     * @throws \OpenCloud\Common\Exceptions\InvalidArgumentError
     * @throws \OpenCloud\Common\Exceptions\ObjectError
     *
     */
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
        
        // @codeCoverageIgnoreStart
        if (!($secret = $this->getService()->getAccount()->getTempUrlSecret())) {
            throw new Exceptions\ObjectError('Cannot produce temporary URL without an account secret.');
        }
        // @codeCoverageIgnoreEnd

        $url  = $this->getUrl();
        $body = sprintf("%s\n%d\n%s", $method, $expiry, $url->getPath());
        $hash = hash_hmac('sha1', $body, $secret);

        return sprintf('%s?temp_url_sig=%s&temp_url_expires=%d', $url, $hash, $expiry);
    }

    public function purge($email = null)
    {
        $cdn = $this->getContainer()->getCdn();

        $url = clone $cdn->getUrl();
        $url->addPath($this->name);

        $headers = ($email !== null) ? array('X-Purge-Email' => $email) : array();

        return $this->getService()
            ->getClient()
            ->delete($url, $headers)
            ->send();
    }

    public function getPublicUrl($type = UrlType::CDN)
    {
        $cdn = $this->container->getCdn();
        
        switch ($type) {
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
        $response = $this->getService()->getClient()
            ->get($this->getUrl())
            ->send();

        $this->content = $response->getBody();
        $this->setMetadata($response->getHeaders(), true);
        $this->setContentType((string) $response->getHeader('Content-Type'));
    }

}