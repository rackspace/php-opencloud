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

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use Guzzle\Http\EntityBody;

/**
 * A container is a storage compartment for your data and provides a way for you 
 * to organize your data. You can think of a container as a folder in Windows® 
 * or a directory in UNIX®. The primary difference between a container and these 
 * other file system concepts is that containers cannot be nested.
 * 
 * A container can also be CDN-enabled (for public access), in which case you
 * will need to interact with a CDNContainer object instead of this one.
 */
class Container extends AbstractContainer
{
    const HEADER_METADATA_PREFIX = 'X-Container-Meta-';
    const HEADER_METADATA_UNSET_PREFIX = 'X-Remove-Container-Meta-';
    
    /**
     * @var CDNContainer|null 
     */
    private $cdn;
    
    public function setCDN(CDNContainer $cdn)
    {
        $this->cdn = $cdn;
        return $this;
    }
    
    public function getCDN()
    {
        if (!$this->cdn) {
            throw new Exceptions\CdnNotAvailableError(
            	Lang::translate('CDN-enabled container is not available')
            );
        }
        
        return $this->cdn;
    }
    
    public function delete($deleteObjects = false)
    {
        if ($deleteObjects === true) {
            $this->deleteAllObjects();
        }
        
        $this->getClient()->delete($this->getUri())
            ->setExceptionHandler(array(
                404 => 'Container not found',
                409 => 'Container must be empty before deleting. Please set the $deleteObjects argument to TRUE.',
                300 => 'Unknown error'
            ))
            ->send();

        return true;
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
        $url = $this->parameterizeCollectionUri($params);
        return $this->getService()->resourceList('DataObject', $url, $this);
    }
    
    
    
    public function enableCDN($ttl = null)
    {
        $url = $this->getService()->CDN()->url() . '/' . rawurlencode($this->name);

        $headers = $this->metadataHeaders();

        if ($ttl) {
           
            // Make sure we're dealing with a real figure
            if (!is_integer($ttl)) {
                throw new Exceptions\CdnTtlError(sprintf(
                    Lang::translate('TTL value [%s] must be an integer'), 
                    $ttl
                ));
            }
            
            $headers['X-TTL'] = $ttl;
        }

        $headers['X-Log-Retention'] = 'True';
        $headers['X-CDN-Enabled']   = 'True';

        // PUT to the CDN container
        $this->getClient()->put($url, $headers)->send();

        // refresh the data
        $this->refresh();

        // return the CDN container object
        $cdn = new CDNContainer($this->getService()->getCDNService(), $this->name);
        $this->setCDN($cdn);
        
        return $cdn;
    }

    /**
     * Disables the containers CDN function. Note that the container will still 
     * be available on the CDN until its TTL expires.
     * 
     * @return true
     */
    public function disableCDN()
    {
        // Set necessary headers
        $headers = array('X-Log-Retention' => 'False', 'X-CDN-Enabled' => 'False');

        // PUT it to the CDN service
        $this->getClient()->put($this->CDNURL(), $headers)
            ->setExpectedResponse(201)
            ->send();
        
        return true;
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
        return $this->getClient()->post($this->url(), $headers)->send();
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
        return $this->getClient()->post($this->url(), $headers)->send();
    }

    /**
     * Returns the CDN URL of the container (if enabled)
     *
     * The CDNURL() is used to manage the container. Note that it is different
     * from the PublicURL() of the container, which is the publicly-accessible
     * URL on the network.
     *
     * @api
     * @return string
     */
    public function CDNURL()
    {
        return $this->getCDN()->url();
    }

    /**
     * Returns the Public URL of the container (on the CDN network)
     *
     */
    public function publicURL()
    {
        return $this->CDNURI();
    }

    /**
     * Returns the CDN info about the container
     *
     * @api
     * @return stdClass
     */
    public function CDNinfo($property = null)
    {
        // Not quite sure why this is here...
        // @codeCoverageIgnoreStart
		if ($this->getService() instanceof CDNService) {
			return $this->metadata;
        }
        // @codeCoverageIgnoreEnd

        // return NULL if the CDN container is not enabled
        if ($this->getCDN()->metadata->Enabled != 'True') {
            return null;
        }

        // check to see if it's set
        if (isset($this->getCDN()->metadata->$property)) {
            return trim($this->getCDN()->metadata->$property);
        } elseif ($property !== null) {
            return null;
        }

        // otherwise, return the whole metadata object
        return $this->getCDN()->metadata;
    }

    /**
     * Returns the CDN container URI prefix
     *
     * @api
     * @return string
     */
    public function CDNURI()
    {
        return $this->CDNinfo('Uri');
    }

    /**
     * Returns the SSL URI for the container
     *
     * @api
     * @return string
     */
    public function SSLURI()
    {
        return $this->CDNinfo('Ssl-Uri');
    }

    /**
     * Returns the streaming URI for the container
     *
     * @api
     * @return string
     */
    public function streamingURI()
    {
        return $this->CDNinfo('Streaming-Uri');
    }

    /**
     * Returns the IOS streaming URI for the container
     *
     * @api
     * @link http://docs.rackspace.com/files/api/v1/cf-devguide/content/iOS-Streaming-d1f3725.html
     * @return string
     */
    public function iosStreamingURI()
    {
        return $this->CDNinfo('Ios-Uri');
    }

    

    /**
     * Refreshes, then associates the CDN container
     */
    public function refresh($id = null, $url = null)
    {
        parent::refresh($id, $url);
        
        // @codeCoverageIgnoreStart
		if ($this->getService() instanceof CDNService) {
			return;
        }
        
        
        if (null !== ($cdn = $this->getService()->CDN())) {
            try {
                $this->cdn = new CDNContainer(
                    $cdn,
                    $this->name
                );
            } catch (Exceptions\ContainerNotFoundError $e) {
                $this->cdn = new CDNContainer($cdn);
                $this->cdn->name = $this->name;
            }
        }
        // @codeCoverageIgnoreEnd
    }
    
    public function getObject($name = null)
    {
        return new DataObject($this, $name);
    }
    
    /**
     * @param array $params
     */
    public function uploadObject(array $options = array())
    {
        // Name is required
        if (empty($options['name'])) {
            throw new Exceptions\InvalidArgumentError('You must provide a name.');
        }

        // As is some form of entity body
        if (!empty($options['path'])) {
            $body = EntityBody::factory($options['path']);
        } elseif (!empty($options['body'])) {
            $body = EntityBody::factory($options['body']);
        } else {
            throw new Exceptions\InvalidArgumentError('You must provide either a path or a body');
        }
        
        // Build upload
        $uploader = UploadBuilder::factory()
            ->setObjectName($options['name'])
            ->setEntityData($body)
            ->setContainer($this);
        
        // Add extra options
        if (!empty($options['metadata'])) {
            $uploader->setOption('metadata', $options['metadata']);
        }
        if (!empty($options['partSize'])) {
            $uploader->setPartSize($options['partSize']);
        }
        if (!empty($options['concurrency'])) {
            $uploader->setConcurrency($options['concurrency']);
        }
        
        var_dump($uploader);die;
        
        return $uploader->upload();
    }

}