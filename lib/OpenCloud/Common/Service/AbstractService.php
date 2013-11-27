<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Service;

use OpenCloud\Common\Base;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Collection\PaginatedIterator;
use OpenCloud\Common\Http\Client;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Http\Message\Formatter;
use Guzzle\Http\Url;
use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This class defines a cloud service; a relationship between a specific OpenStack
 * and a provided service, represented by a URL in the service catalog.
 *
 * Because Service is an abstract class, it cannot be called directly. Provider
 * services such as Rackspace Cloud Servers or OpenStack Swift are each
 * subclassed from Service.
 */
abstract class AbstractService extends Base
{
    const DEFAULT_REGION   = 'DFW';
    const DEFAULT_URL_TYPE = 'publicURL';
    
    /**
     * @var OpenCloud\Common\Http\Client The client which interacts with the API.
     */
    protected $client;
    
    /**
     * @var string The type of this service, as set in Catalog. 
     */
    private $type;
    
    /**
     * @var string The name of this service, as set in Catalog.
     */
    private $name;
    
    /**
     * @var string The chosen region(s) for this service.
     */
    private $region;
    
    /**
     * @var string Either 'publicURL' or 'privateURL'.
     */
    private $urlType;
    
    /**
     * @var \OpenCloud\Common\Service\Endpoint The endpoints for this service.
     */
    private $endpoint;
    
    /**
     * @var array Namespaces for this service.
     */
    protected $namespaces = array();

    /**
     * @var array A collection of resource models that this service has control over.
     */
    protected $resources = array();

    /**
     * Creates a service object, based off the specified client.
     *
     * The service's URL is defined in the client's serviceCatalog; it
     * uses the $type, $name, $region, and $urlType to find the proper endpoint
     * and set it. If it cannot find a URL in the service catalog that matches
     * the criteria, then an exception is thrown.
     *
     * @param Client $client  Client object
     * @param string $type    Service type (e.g. 'compute')
     * @param string $name    Service name (e.g. 'cloudServersOpenStack')
     * @param string $region  Service region (e.g. 'DFW', 'ORD', 'IAD', 'LON', 'SYD')
     * @param string $urlType Either 'publicURL' or 'privateURL'
     */
    public function __construct(Client $client, $type = null, $name = null, $region = null, $urlType = null)
    {
        $this->setClient($client);

        $this->type = $type ?: static::DEFAULT_TYPE;
        $this->name = $name ?: static::DEFAULT_NAME;
        $this->region = $region ?: static::DEFAULT_REGION;
        $this->urlType = $urlType ?: static::DEFAULT_URL_TYPE;

        $this->endpoint = $this->findEndpoint();
        $this->client->setBaseUrl($this->getBaseUrl());

        if ($this instanceof EventSubscriberInterface) {
            $this->client->getEventDispatcher()->addSubscriber($this);
        }
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return OpenCloud\Common\Http\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrlType()
    {
        return $this->urlType;
    }

    /**
     * @return Endpoint|OpenCloud\Common\Service\Endpoint
     */
    public function getEndpoint()
    {
	    return $this->endpoint;
    }
    
    /**
     * @deprecated
     */
    public function region()
    {
        return $this->getRegion();
    }

    /**
     * @deprecated
     */
    public function name()
    {
        return $this->name;
    }
    
    /**
     * Returns the URL for the Service
     *
     * @param  string $path  URL path segment
     * @param  array  $query Array of query pairs
     * @return Guzzle\Http\Url
     */
    public function getUrl($path = null, array $query = array())
    {
        return Url::factory($this->getBaseUrl())
            ->addPath($path)
            ->setQuery($query);
    }
    
    /**
     * @deprecated
     */
    public function url($path = null, array $query = array()) 
    {
        return $this->getUrl($path, $query);
    }

    /**
     * Returns the /extensions for the service
     *
     * @api
     * @return array of objects
     */
    public function getExtensions()
    {
        $ext = $this->getMetaUrl('extensions');
        return (is_object($ext) && isset($ext->extensions)) ? $ext->extensions : array();
    }

    /**
     * Returns the limits for the service
     *
     * @return array of limits
     */
    public function limits()
    {
        $limits = $this->getMetaUrl('limits');
        return (is_object($limits)) ? $limits->limits : array();
    }

    /**
     * Returns a list of supported namespaces
     *
     * @return array
     */
    public function namespaces()
    {
        return (isset($this->namespaces) && is_array($this->namespaces)) 
            ? $this->namespaces 
            : array();
    }

    /**
     * Extracts the appropriate endpoint from the service catalog based on the
     * name and type of a service, and sets for further use.
     *
     * @return \OpenCloud\Common\Service\Endpoint
     * @throws \OpenCloud\Common\Exceptions\EndpointError
     */
    private function findEndpoint()
    {
        if (!$this->getClient()->getCatalog()) {
            $this->getClient()->authenticate();
        }
        
        $catalog = $this->getClient()->getCatalog();

        // Search each service to find The One
        foreach ($catalog->getItems() as $service) {
            if ($service->hasType($this->type) && $service->hasName($this->name)) {
                return Endpoint::factory($service->getEndpointFromRegion($this->region));
            }
        }

        throw new Exceptions\EndpointError(sprintf(
            'No endpoints for service type [%s], name [%s], region [%s] and urlType [%s]',
            $this->type,
            $this->name,
            $this->region,
            $this->urlType
        ));
    }

    /**
     * Constructs a specified URL from the subresource
     *
     * Given a subresource (e.g., "extensions"), this constructs the proper
     * URL and retrieves the resource.
     *
     * @param string $resource The resource requested; should NOT have slashes
     *      at the beginning or end
     * @return \stdClass object
     */
    private function getMetaUrl($resource)
    {
        $url = clone $this->getBaseUrl();
        $url->addPath($resource);
        try {
            $response = $this->getClient()->get($url)->send();
            return Formatter::decode($response);
        } catch (BadResponseException $e) {
            // @codeCoverageIgnoreStart
            return array();
            // @codeCoverageIgnoreEnd
        }
    }
    
    /**
     * Get all associated resources for this service.
     * 
     * @access public
     * @return void
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Internal method for accessing child namespace from parent scope.
     * 
     * @return type
     */
    protected function getCurrentNamespace()
    {
        $namespace = get_class($this);
        return substr($namespace, 0, strrpos($namespace, '\\'));
    }
    
    /**
     * Resolves FQCN for local resource.
     *
     * @param  $resourceName
     * @return string
     * @throws \OpenCloud\Common\Exceptions\UnrecognizedServiceError
     */
    protected function resolveResourceClass($resourceName)
    {
        $className = substr_count($resourceName, '\\') 
            ? $resourceName 
            : $this->getCurrentNamespace() . '\\Resource\\' . ucfirst($resourceName);
        
        if (!class_exists($className)) {
            throw new Exceptions\UnrecognizedServiceError(sprintf(
                '%s resource does not exist, please try one of the following: %s', 
                $resourceName, 
                implode(', ', $this->getResources())
            ));
        }
        
        return $className;
    }
    
    /**
     * Factory method for instantiating resource objects.
     *
     * @param  string $resourceName
     * @param  mixed $info (default: null)
     * @param  mixed $parent The parent object
     * @return object
     */
    public function resource($resourceName, $info = null, $parent = null)
    {
        $className = $this->resolveResourceClass($resourceName);

        $resource = new $className($this);
        if ($parent) {
            $resource->setParent($parent);
        }

        $resource->populate($info);

        return $resource;
    }
    
    /**
     * Factory method for instantiating a resource collection.
     *
     * @param string      $resourceName
     * @param string|null $url
     * @param string|null $service
     * @return OpenCloud\Common\Collection
     */
    public function resourceList($resourceName, $url = null, $parent = null)
    {
        $className = $this->resolveResourceClass($resourceName);
        return $this->collection($className, $url, $parent);
    }

    /**
     * Get the base URL for this service, based on the set URL type.
     * @return \Guzzle\Http\Url
     * @throws \OpenCloud\Common\Exceptions\ServiceException
     */
    public function getBaseUrl()
    {
        $url = ($this->urlType == 'publicURL') 
            ? $this->endpoint->getPublicUrl() 
            : $this->endpoint->getPrivateUrl();

        if ($url === null) {
            throw new Exceptions\ServiceException(sprintf(
	        	'The base %s could not be found. Perhaps the service '
	        	. 'you are using requires a different URL type, or does '
	        	. 'not support this region.',
	        	$this->urlType
		    ));
        }
        
        return $url;
    }

    /**
     * @codeCoverageIgnore
     */
    public function collection($class, $url = null, $parent = null, $data = null)
    {
        if (!$parent) {
            $parent = $this;
        }

        $resource = $this->resolveResourceClass($class);

        if (!$url) {
            $url = $parent->getUrl($resource::resourceName());
        }

        $options = $this->makeResourceIteratorOptions($this->resolveResourceClass($class));
        $options['baseUrl'] = $url;

        return PaginatedIterator::factory($parent, $options, $data);
    }

}