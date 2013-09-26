<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common;

use OpenCloud\Common\Base;
use OpenCloud\Common\Lang;
use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions;
use Guzzle\Http\Exception\RequestException;

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
abstract class Service extends Base
{
    const DEFAULT_NAME = '';
    const DEFAULT_REGION   = 'DFW';
    const DEFAULT_URL_TYPE = 'publicURL';
    
    protected $client;
    private $service_type;
    private $service_name;
    private $service_region;
    protected $service_url;

    protected $_namespaces = array();

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
        $this->service_type = $type;
        $this->service_name = $name;
        $this->service_region = $region;
        $this->service_url = $this->getEndpoint($type, $name, $region, $urltype);
        
        // Let client know what service is relying on it
        $this->client->setServiceType($type);
    }
    
    /**
     * Set this service's connection.
     * 
     * @param type $connection
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * Get this service's connection.
     * 
     * @return type
     */
    public function getClient()
    {
        return $this->client;
    }
    
    /**
     * Returns the URL for the Service
     *
     * @param string $resource optional sub-resource
     * @param array $query optional k/v pairs for query strings
     * @return string
     */
    public function url($resource = '', array $param = array())
    {
        $baseurl = $this->service_url;

		// use strlen instead of boolean test because '0' is a valid name
        if (strlen($resource) > 0) {
            $baseurl = Lang::noslash($baseurl).'/'.$resource;
        }

        if (!empty($param)) {
            $baseurl .= '?'.$this->makeQueryString($param);
        }

        return $baseurl;
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
        if (strlen($object) == 0) {
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
            // @codeCoverageIgnoreStart
            $data = $object;
            // @codeCoverageIgnoreEnd
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
     * returns the Region associated with the service
     *
     * @api
     * @return string
     */
    public function region()
    {
        return $this->service_region;
    }

    /**
     * returns the serviceName associated with the service
     *
     * This is used by DNS for PTR record lookups
     *
     * @api
     * @return string
     */
    public function name()
    {
        return $this->service_name;
    }

    /**
     * Returns a list of supported namespaces
     *
     * @return array
     */
    public function namespaces()
    {
        return (isset($this->_namespaces) && is_array($this->_namespaces)) ? $this->_namespaces : array();
    }

    /**
     * Given a service type, name, and region, return the url
     *
     * This function ensures that services are represented by an entry in the
     * service catalog, and NOT by an arbitrarily-constructed URL.
     *
     * Note that it will always return the first match found in the
     * service catalog (there *should* be only one, but you never know...)
     *
     * @param string $type The OpenStack service type ("compute" or
     *      "object-store", for example
     * @param string $name The name of the service in the service catlog
     * @param string $region The region of the service
     * @param string $urltype The URL type; defaults to "publicURL"
     * @return string The URL of the service
     */
    private function getEndpoint($type, $name, $region, $urltype = null)
    {
        $catalog = $this->getClient()->getCatalog();
        
        if (empty($urltype)) {
            $urltype = RAXSDK_URL_PUBLIC;
        }

        // Search each service to find The One
        foreach ($catalog as $service) {
            // Find the service by comparing the type ("compute") and name ("openstack")
            if (!strcasecmp($service->type, $type) && !strcasecmp($service->name, $name)) {
                foreach($service->endpoints as $endpoint) {
                    // Only set the URL if:
                    // a. It is a regionless service (i.e. no region key set)
                    // b. The region matches the one we want
                    if (isset($endpoint->$urltype) && 
                        (!isset($endpoint->region) || !strcasecmp($endpoint->region, $region))
                    ) {
                        $url = $endpoint->$urltype;
                    }
                }
            }
        }

        // error if not found
        if (empty($url)) {
            throw new Exceptions\EndpointError(sprintf(
                'No endpoints for service type [%s], name [%s], region [%s] and urlType [%s]',
                $type,
                $name,
                $region,
                $urltype
            ));
        }
        
        return $url;
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
        $urlBase = $this->getEndpoint(
            $this->service_type,
            $this->service_name,
            $this->service_region,
            RAXSDK_URL_PUBLIC
        );

        $url = Lang::noslash($urlBase) . '/' . $resource;
        
        try {
            $response = $this->getClient()->get($url)->send();
        } catch (RequestException $e) {}
        
        // check for NOT FOUND response
        if ($response->getStatusCode() == 404) {
            return array();
        }

        // we're good; proceed
        $object = $response->getBody(true);
        $this->checkJsonError();

        return $object;
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

}
