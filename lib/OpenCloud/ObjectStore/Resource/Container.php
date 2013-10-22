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
use OpenCloud\Common\Constants\Size;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Collection;
use OpenCloud\Common\Http\Message\Response;
use OpenCloud\ObjectStore\Upload\TransferBuilder;
use OpenCloud\Common\Service\AbstractService;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * A container is a storage compartment for your data and provides a way for you 
 * to organize your data. You can think of a container as a folder in Windows 
 * or a directory in Unix. The primary difference between a container and these 
 * other file system concepts is that containers cannot be nested.
 * 
 * A container can also be CDN-enabled (for public access), in which case you
 * will need to interact with a CDNContainer object instead of this one.
 */
class Container extends AbstractContainer
{
    const METADATA_LABEL = 'Container';
    
    /**
     * @var CDNContainer|null 
     */
    private $cdn;
    
    public static function fromResponse(Response $response, AbstractService $service)
    {
        $self = parent::fromResponse($response, $service);
        
        $segments = Url::factory($response->getEffectiveUrl())->getPathSegments();
        $self->name = end($segments);
        
        return $self;
    }
    
    public function getCdn()
    {
        if (!$this->isCdnEnabled() || !$this->cdn) {
            throw new Exceptions\CdnNotAvailableError(
            	'Either this container is not CDN-enabled or the CDN is not available'
            );
        }
        
        return $this->cdn;
    }
    
    public function getObjectCount()
    {
        return $this->metadata->getProperty('Object-Count');
    }
    
    public function getBytesUsed()
    {
        return $this->metadata->getProperty('Bytes-Used');
    }
    
    public function getCountQuota()
    {
        return $this->metadata->getProperty('Quota-Count');
    }
    
    public function getBytesQuota()
    {
        return $this->metadata->getProperty('Quota-Bytes');
    }
    
    public function delete($deleteObjects = false)
    {
        if ($deleteObjects === true) {
            $this->deleteAllObjects();
        }
        
        return $this->getClient()->delete($this->getUrl())
            ->setExceptionHandler(array(
                404 => 'Container not found',
                409 => 'Container must be empty before deleting. Please set the $deleteObjects argument to TRUE.',
                300 => 'Unknown error'
            ))
            ->send();
    }
    
    public function deleteAllObjects()
    {
        $requests = array();
        
        $list = $this->objectList();
        
        while ($object = $list->next()) {
            $requests[] = $this->getClient()->delete($object->getUrl());
        }

        return $this->getClient()->send($requests);
    }
    
    /**
     * Creates a Collection of objects in the container
     *
     * @param array $params associative array of parameter values.
     * * account/tenant - The unique identifier of the account/tenant.
     * * container- The unique identifier of the container.
     * * limit (Optional) - The number limit of results.
     * * marker (Optional) - Value of the marker, that the object names
     *      greater in value than are returned.
     * * end_marker (Optional) - Value of the marker, that the object names
     *      less in value than are returned.
     * * prefix (Optional) - Value of the prefix, which the returned object
     *      names begin with.
     * * format (Optional) - Value of the serialized response format, either
     *      json or xml.
     * * delimiter (Optional) - Value of the delimiter, that all the object
     *      names nested in the container are returned.
     * @link http://api.openstack.org for a list of possible parameter
     *      names and values
     * @return OpenCloud\Collection
     * @throws ObjFetchError
     */
    public function objectList(array $params = array())
    {
        $objects = $this->getClient()
            ->get($this->getUrl(null, $params))
            ->send()
            ->getDecodedBody();

        return new Collection($this, 'OpenCloud\ObjectStore\Resource\DataObject', $objects);
    }
    
    public function enableLogging()
    {
        return $this->saveMetadata($this->appendToMetadata(array(
            self::HEADER_ACCESS_LOGS => true
        )));
    }
    
    public function disableLogging()
    {
        return $this->saveMetadata($this->appendToMetadata(array(
            self::HEADER_ACCESS_LOGS => false
        )));
    }
    
    public function enableCdn($ttl = null)
    {
        $headers = array('X-CDN-Enabled' => 'True');
        if ($ttl) {
            $headers['X-TTL'] = (int) $ttl;
        }

        $this->getClient()->put($this->getCdnService()->getUrl($this->name), $headers)->send();
        $this->refresh();
    }

    /**
     * Disables the containers CDN function. Note that the container will still 
     * be available on the CDN until its TTL expires.
     * 
     * @return true
     */
    public function disableCdn()
    {
        return $this->getClient()
            ->put($this->getCdnService()->getUrl($this->name), array('X-CDN-Enabled' => 'False'))
            ->send();
    }

    /**
     * Creates a static website from the container
     *
     * @link http://docs.rackspace.com/files/api/v1/cf-devguide/content/Create_Static_Website-dle4000.html
     * @param string $index the index page (starting page) of the website
     * @return \OpenCloud\HttpResponse
     */
    public function createStaticSite($indexHtml)
    {
        $headers = array('X-Container-Meta-Web-Index' => $indexHtml);
        return $this->getClient()->post($this->getUrl(), $headers)->send();
    }

    /**
     * Sets the error page(s) for the static website
     *
     * @api
     * @link http://docs.rackspace.com/files/api/v1/cf-devguide/content/Set_Error_Pages_for_Static_Website-dle4005.html
     * @param string $name the name of the error page
     * @return \OpenCloud\HttpResponse
     */
    public function staticSiteErrorPage($name)
    {
        $headers = array('X-Container-Meta-Web-Error' => $name);
        return $this->getClient()->post($this->getUrl(), $headers)->send();
    }

    /**
     * Refreshes, then associates the CDN container
     */
    public function refresh($id = null, $url = null)
    {
        $headers = $this->createRefreshRequest($this->name)->send()->getHeaders();
        $this->setMetadata($headers, true);
        
        try {
            
            $cdn = new CDNContainer($this->getService()->getCDNService());
            $cdn->setName($this->name);
            
            $response = $cdn->createRefreshRequest($this->name)->send();
            
            if ($response->isSuccessful()) {
                $this->cdn = $cdn;
                $this->cdn->setMetadata($response->getHeaders(), true);
            }
            
        } catch (ClientErrorResponseException $e) {}   
    }
    
    public function dataObject($info = null)
    {
        return new DataObject($this, $info);
    }
    
    /**
     * Retrieve an object from the API. Apart from using the name as an 
     * identifier, you can also specify additional headers that will be used 
     * fpr a conditional GET request. These are
     * 
     * * `If-Match'
     * * `If-None-Match'
     * * `If-Modified-Since'
     * * `If-Unmodified-Since'
     * * `Range'  For example: 
     *      bytes=-5    would mean the last 5 bytes of the object
     *      bytes=10-15 would mean 5 bytes after a 10 byte offset
     *      bytes=32-   would mean all dat after first 32 bytes
     * 
     * These are also documented in RFC 2616.
     * 
     * @param type $name
     * @param array $headers
     * @return type
     */
    public function getObject($name, array $headers = array())
    {
        $response = $this->getClient()
            ->get($this->getUrl($name), $headers)
            ->send();
        
        return $this->dataObject()->setName($name)
            ->setContent($response->getBody())
            ->setMetadata($response->getHeaders(), true);
    }
    
    public function uploadObjects(array $files, array $headers = array())
    {
        $requests = array();
        
        foreach ($files as $entity) {
            
            if (empty($entity['name'])) {
	            throw new Exceptions\InvalidArgumentError('You must provide a name.');
	        }
            
            if (!empty($entity['path']) && file_exists($entity['path'])) {
            	$body = fopen($entity['path'], 'r+');
	        } elseif (!empty($entity['body'])) {
	            $body = $entity['body'];
	        } else {
	            throw new Exceptions\InvalidArgumentError('You must provide either a readable path or a body');
	        }
	        
            $entityBody = EntityBody::factory($body);
            
            // @codeCoverageIgnoreStart
            if ($entityBody->getContentLength() >= 5 * Size::GB) {
                throw new Exceptions\InvalidArgumentError(
                    'For multiple uploads, you cannot upload more than 5GB per '
                    . ' file. Use the UploadBuilder for larger files.'
                );
            }
            // @codeCoverageIgnoreEnd
            
            $url = clone $this->getUrl();
            $url->addPath($entity['name']);

            $requests[] = $this->getClient()->put($url, $headers, $entityBody);
        }
        
        return $this->getClient()->send($requests);
    }
    
    public function uploadObject($name, $data, array $headers = array())
    {
        $entityBody = EntityBody::factory($data);
        
        $url = clone $this->getUrl();
        $url->addPath($name);
        
        return $this->getClient()->put($url, $headers, $entityBody)->send();
    }
    
    public function setupObjectTransfer(array $options = array())
    {
        // Name is required
        if (empty($options['name'])) {
            throw new Exceptions\InvalidArgumentError('You must provide a name.');
        }

        // As is some form of entity body
        if (!empty($options['path']) && file_exists($options['path'])) {
            $body = fopen($options['path'], 'r+');
        } elseif (!empty($options['body'])) {
            $body = $options['body'];
        } else {
            throw new Exceptions\InvalidArgumentError('You must provide either a readable path or a body');
        }
        
        // Build upload
        $transfer = TransferBuilder::newInstance()
            ->setOption('objectName', $options['name'])
            ->setEntityBody(EntityBody::factory($body))
            ->setContainer($this);
        
        // Add extra options
        if (!empty($options['metadata'])) {
            $transfer->setOption('metadata', $options['metadata']);
        }
        if (!empty($options['partSize'])) {
            $transfer->setOption('partSize', $options['partSize']);
        }
        if (!empty($options['concurrency'])) {
            $transfer->setOption('concurrency', $options['concurrency']);
        }
        if (!empty($options['progress'])) {
            $transfer->setOption('progress', $options['progress']);
        }

        return $transfer->build();
    }

}