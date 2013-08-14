<?php
/**
 * An abstraction that defines a cloud service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common;

use OpenCloud\Common\Base;
use OpenCloud\Common\Lang;
use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions;

/**
 * This class defines a "service"—a relationship between a specific OpenStack
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

    protected $conn;
    private $service_type;
    private $service_name;
    private $service_region;
    private $service_url;

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
    public function __construct(
        OpenStack $conn,
        $type,
        $name,
        $region,
        $urltype = RAXSDK_URL_PUBLIC,
        $customServiceUrl = null
    ) {
        $this->conn = $conn;
        $this->service_type = $type;
        $this->service_name = $name;
        $this->service_region = $region;
        $this->service_url = $customServiceUrl ?: $this->get_endpoint($type, $name, $region, $urltype);
    }

    /**
    * Returns the connection used to create the Service.
    *
    * @return OpenCloud\OpenStack
    */
    public function connection() 
    {
        return $this->conn;
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

        if ($resource) {
            $baseurl = Lang::noslash($baseurl).'/'.$resource;
        }

        if (!empty($param)) {
            $baseurl .= '?'.$this->MakeQueryString($param);
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
     * Performs an authenticated request
     *
     * This method handles the addition of authentication headers to each
     * request. It always adds the X-Auth-Token: header and will add the
     * X-Auth-Project-Id: header if there is a tenant defined on the
     * connection.
     *
     * @param string $url The URL of the request
     * @param string $method The HTTP method (defaults to "GET")
     * @param array $headers An associative array of headers
     * @param string $body An optional body for POST/PUT requests
     * @return \OpenCloud\HttpResult
     */
    public function request(
    	$url,
    	$method = 'GET',
    	array $headers = array(),
    	$body = null
    ) {

        $headers['X-Auth-Token'] = $this->conn->Token();

        if ($tenant = $this->conn->Tenant()) {
            $headers['X-Auth-Project-Id'] = $tenant;
        }

        return $this->conn->request($url, $method, $headers, $body);
    }

    /**
     * returns a collection of objects
     *
     * @param string $class the class of objects to fetch
     * @param string $url (optional) the URL to retrieve
     * @param mixed $parent (optional) the parent service/object
     * @param array $parm (optional) array of key/value pairs to use as
     *      query strings
     * @return \OpenCloud\Collection
     */
    public function collection($class, $url = null, $parent = null, array $parm = array())
    {
        // set the element name
        $collection = $class::JsonCollectionName();
        $element = $class::JsonCollectionElement();

        // save the parent
        if (!isset($parent)) {
            $parent = $this;
        }

        // determine the URL
        if (!$url) {
            $url = $parent->Url($class::ResourceName());
        }
        
        // add query string parameters
        if (count($parm)) {
            $url .= '?' . $this->MakeQueryString($parm);
        }

        // save debug info
        $this->getLogger()->info(
            '{class}:Collection({url}, {collectionClass}, {collectionName})', 
            array(
                'class' => get_class($this), 
                'url'   => $url, 
                'collectionClass' => $class, 
                'collectionName'  => $collection
            )
        );

        // fetch the list
        $response = $this->Request($url);

        $this->getLogger()->info('Response {status} [{body}]', array(
            'status' => $response->httpStatus(), 
            'body'   => $response->httpBody()
        ));

        // check return code
        if ($response->httpStatus() > 204) {
            throw new Exceptions\CollectionError(sprintf(
                Lang::translate('Unable to retrieve [%s] list from [%s], status [%d] response [%s]'),
                $class,
                $url,
                $response->httpStatus(),
                $response->httpBody()
            ));
        }
        
        // handle empty response
        if (strlen($response->httpBody()) == 0) {
            return new Collection($parent, $class, array());
        }

        // parse the return
        $obj = json_decode($response->httpBody());
        if ($this->checkJsonError()) {
            return false;
        }
        
        // see if there is a "next" link
        if (isset($obj->links)) {
            if (is_array($obj->links)) {
                foreach($obj->links as $link) {
                    if (isset($link->rel) && ($link->rel=='next')) {
                        if (isset($link->href)) {
                            $next_page_url = $link->href;
                        } else {
                            throw new Exceptions\DomainError(Lang::translate('unexpected [links] found with no [href]'));
                        }
                    }
                }
            }
        }

        // and say goodbye
        if (!$collection) {
            $coll_obj = new Collection($parent, $class, $obj);
        } elseif (isset($obj->$collection)) {
            if (!$element) {
                $coll_obj = new Collection($parent, $class, $obj->$collection);
            } else {
                // handle element levels
                $array = array();
                foreach($obj->$collection as $item) {
                    $subValues = $item->$element;
                    unset($item->$element);
                    $array[] = array_merge((array)$item, (array)$subValues);
                }
 
                $coll_obj = new Collection($parent, $class, $array);
            }
        } else {
            $coll_obj = new Collection($parent, $class, array());
        }
        
        // if there's a $next_page_url, then we need to establish a
        // callback method
        if (isset($next_page_url)) {
            $coll_obj->SetNextPageCallback(array($this, 'Collection'), $next_page_url);
        }

        return $coll_obj;
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
        if (!isset($this->_namespaces)) {
            return array();
        }

        if (is_array($this->_namespaces)) {
            return $this->_namespaces;
        }

        return array();
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
    private function get_endpoint($type, $name, $region, $urltype = 'publicURL')
    {
        $found = 0;
        $catalog = $this->conn->serviceCatalog();

        // search each service to find The One
        foreach ($catalog as $service) {
            // first, compare the type ("compute") and name ("openstack")
            if (!strcasecmp($service->type, $type) && !strcasecmp($service->name, $name)) {
                // found the service, now we need to find the region
                foreach($service->endpoints as $endpoint) {
                    // regionless service
                    if (!isset($endpoint->region) && isset($endpoint->$urltype)) {
                        ++$found;
                        return $endpoint->$urltype;
                    } elseif (!strcasecmp($endpoint->region, $region) && isset($endpoint->$urltype)) {
                        // now we have a match! Yay!
                        ++$found;
                        return $endpoint->$urltype;
                    }
                }
            }
        }

        // error if not found
        if (!$found) {
            throw new Exceptions\EndpointError(
                sprintf(Lang::translate('No endpoints for service type [%s], name [%s], region [%s] and urlType [%s]'),
                $type,
                $name,
                $region,
                $urltype
            ));
        }
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
        $urlbase = $this->get_endpoint(
            $this->service_type,
            $this->service_name,
            $this->service_region,
            RAXSDK_URL_PUBLIC
        );

        if ($urlbase == '') {
            return array();
        }

        $ext_url = Lang::noslash($urlbase) . '/' . $resource;

        $response = $this->request($ext_url);

        // check for NOT FOUND response
        if ($response->httpStatus() == 404) {
            return array();
        }

        // check for error status
        if ($response->httpStatus() >= 300) {
            throw new Exceptions\HttpError(sprintf(
                Lang::translate('Error accessing [%s] - status [%d], response [%s]'),
                $urlbase,
                $response->httpStatus(),
                $response->httpBody()
            ));
        }

        // we're good; proceed
        $obj = json_decode($response->HttpBody());

        if ($this->CheckJsonError()) {
            return false;
        }

        return $obj;
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