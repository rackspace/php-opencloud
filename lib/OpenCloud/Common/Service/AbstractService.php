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
use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions;
use Guzzle\Http\Exception\ClientErrorResponseException;
use OpenCloud\Common\Collection;
use Guzzle\Http\Url;

/**
 * This class defines a cloud service; a relationship between a specific OpenStack
 * and a provided service, represented by a URL in the service catalog.
 *
 * Because Service is an abstract class, it cannot be called directly. Provider
 * services such as Rackspace Cloud Servers or OpenStack Swift are each
 * subclassed from Service.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
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
     * @var string|array The chosen region(s) for this service.
     */
    private $region;
    
    /**
     * @var string Either 'publicURL' or 'privateURL'.
     */
    private $urlType;
    
    /**
     * @var Endpoint The endpoints for this service.
     */
    private $endpoint;
    
    /**
     * @var array Namespaces for this service.
     */
    protected $namespaces = array();

    /**
     * Creates a service on the specified connection
     *
     * Usage: `$x = new Service($conn, $type, $name, $region, $urltype);`
     * The service's URL is defined in the OpenStack's serviceCatalog; it
     * uses the $type, $name, $region, and $urltype to find the proper URL
     * and set it. If it cannot find a URL in the service catalog that matches
     * the criteria, then an exception is thrown.
     *
     * @param OpenStack $conn - a Connection object
     * @param string $type - the service type (e.g., "compute")
     * @param string $name - the service name (e.g., "cloudServersOpenStack")
     * @param string $region - the region (e.g., "ORD")
     * @param string $urltype - the specified URL from the catalog
     *      (e.g., "publicURL")
     */
    public function __construct($client, $type, $name, $region, $urltype = RAXSDK_URL_PUBLIC) 
    {
        $this->client = $client;
        $this->type = $type;
        $this->name = $name;
        $this->region = $region;
        $this->urlType = $urltype;
        
        $this->endpoint = $this->findEndpoint();
        $this->client->setBaseUrl($this->getBaseUrl());
    }
    
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
    
    public function getClient()
    {
        return $this->client;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getRegion()
    {
        return $this->region;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getEndpoint()
    {
	    return $this->endpoint;
    }
    
    /**
     * Backwards comp.
     */
    public function region()
    {
        return $this->region;
    }

    /**
     * Backwards comp.
     */
    public function name()
    {
        return $this->name;
    }
    
    /**
     * Returns the URL for the Service
     *
     * @param string $resource optional sub-resource
     * @param array $query optional k/v pairs for query strings
     * @return string
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
    public function extensions()
    {
        $ext = $this->getMetaUrl('extensions');
        return (is_object($ext) && isset($ext->extensions)) ? $ext->extensions : array();
    }

    /**
     * Returns the /limits for the service
     *
     * @api
     * @return array of limits
     */
    public function limits()
    {
        $limits = $this->getMetaUrl('limits');
        return (is_object($limits)) ? $limits->limits : array();
    }

    /**
     * returns a collection of objects
     *
     * @param string $class the class of objects to fetch
     * @param string $url (optional) the URL to retrieve
     * @param mixed $parent (optional) the parent service/object
     * @return OpenCloud\Common\Collection
     */
    public function collection($class, $url = null, $parent = null)
    {

        // Set the element names
        $collectionName = $class::JsonCollectionName();
        $elementName    = $class::JsonCollectionElement();

        // Set the parent if empty
        if (!$parent) {
            $parent = $this;
        }

        // Set the URL if empty
        if (!$url) {
            $url = $parent->url($class::resourceName());
        }

        // Save debug info
        $this->getLogger()->info(
            '{class}:Collection({url}, {collectionClass}, {collectionName})',
            array(
                'class' => get_class($this),
                'url'   => $url,
                'collectionClass' => $class,
                'collectionName'  => $collectionName
            )
        );

        // Fetch the list
        $response = $this->getClient()->get($url)->send();
        
        // Handle empty response
        $object = $response->getDecodedBody();
        
        if (empty($object)) {
            return new Collection($parent, $class, array());
        }
        
        // See if there's a "next" link
        // Note: not sure if the current API offers links as top-level structures;
        //       might have to refactor to allow $nextPageUrl as method argument
        // @codeCoverageIgnoreStart
        if (isset($object->links) && is_array($object->links)) {
            foreach($object->links as $link) {
                if (isset($link->rel) && $link->rel == 'next') {
                    if (isset($link->href)) {
                        $nextPageUrl = $link->href;
                    } else {
                        $this->getLogger()->warning(
                            'Unexpected [links] found with no [href]'
                        );
                    }
                }
            }
        }
        // @codeCoverageIgnoreEnd
        
        // How should we populate the collection?
        $data = array();

        if (!$collectionName || is_array($object)) {
            // No element name, just a plain object/array
            $data = (array) $object;
        } elseif (isset($object->$collectionName)) {
            if (!$elementName) {
                // The object has a top-level collection name only
                $data = $object->$collectionName;
            } else {
                // The object has element levels which need to be iterated over
                $data = array();
                foreach($object->$collectionName as $item) {
                    $subValues = $item->$elementName;
                    unset($item->$elementName);
                    $data[] = array_merge((array)$item, (array)$subValues);
                }
            }
        }

        $collectionObject = new Collection($parent, $class, $data);
        
        // if there's a $nextPageUrl, then we need to establish a callback
        // @codeCoverageIgnoreStart
        if (!empty($nextPageUrl)) {
            $collectionObject->setNextPageCallback(array($this, 'Collection'), $nextPageUrl);
        }
        // @codeCoverageIgnoreEnd

        return $collectionObject;
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
     * @throws Exceptions\EndpointError
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
        // Note: try and use Guzzle's URI class
        $url = $this->getBaseUrl() . '/' . $resource;
        
        try {
            $response = $this->getClient()->get($url)->send();
        } catch (ClientErrorResponseException $e) {
            return array();
        }
        
        // we're good; proceed
        return $response->getDecodedBody();
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
     * Resolves fully-qualified classname for associated local resource.
     * 
     * @param  string $resourceName
     * @return string
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
     * @access public
     * @param  string $resourceName
     * @param  mixed $info (default: null)
     * @return object
     */
    public function resource($resourceName, $info = null)
    {
        $className = $this->resolveResourceClass($resourceName);
        return new $className($this, $info);
    }
    
    /**
     * Factory method for instantiate a resource collection.
     * 
     * @param  string $resourceName
     * @param  string|null $url
     * @return Collection
     */
    public function resourceList($resourceName, $url = null, $service = null)
    {
        $className = $this->resolveResourceClass($resourceName);
        return $this->collection($className, $url, $service);
    }
    
    public function getBaseUrl()
    {
        $url = ($this->urlType == 'publicURL') 
            ? $this->endpoint->getPublicUrl() 
            : $this->endpoint->getPrivateUrl();
        
        if ($url === null) {
	        throw new Exceptions\ServiceException(sprintf(
	        	'The base %s  could not be found. Perhaps the service'
	        	. 'you are using requires a different URL type, or does '
	        	. 'not support this region.',
	        	$this->urlType
		    ));
        }
        
        return $url;
    }

}
