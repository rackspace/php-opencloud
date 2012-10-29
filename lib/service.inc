<?php
/**
 * An abstraction that defines a cloud service
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

require_once('base.inc');

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
abstract class Service extends Base {
	private
		$conn,
		$service_type,
		$service_name,
		$service_region,
		$service_url;

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
		OpenStack $conn, $type, $name, $region, $urltype=RAXSDK_URL_PUBLIC) {
		$this->conn = $conn;
		$this->service_type = $type;
		$this->service_name = $name;
		$this->service_region = $region;
		$catalog = $conn->serviceCatalog();
		$this->service_url = $this->get_endpoint(
		        $type, $name, $region, $urltype);
	} // public function __construct()

	/**
	 * Returns the URL for the Service
	 *
	 * @return string
	 */
	public function Url() {
		return $this->service_url;
	} // public function Url()

	/**
	 * Returns the /extensions for the service
	 *
	 * @api
	 * @return array of objects
	 */
	public function Extensions() {
		$ext = $this->GetMetaUrl('extensions');
		if (is_object($ext) && isset($ext->extensions))
			return $ext->extensions;
		else
			return array();
	} // public function Extensions()

	/**
	 * Returns the /limits for the service
	 *
	 * @api
	 * @return array of limits
	 */
	public function Limits() {
		$lim = $this->GetMetaUrl('limits');
		if (is_object($lim))
			return $lim->limits;
		else
			return array();
	} // public function Limits()

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
	public function Request($url,$method='GET',$headers=array(),$body=NULL) {
		$headers['X-Auth-Token'] = $this->conn->Token();
		if ($tenant = $this->conn->Tenant())
			$headers['X-Auth-Project-Id'] = $tenant;
		return $this->conn->Request($url, $method, $headers, $body);
	} // function Request()

	/**
	 * returns a collection of objects
	 *
	 * @param string $url the URL to retrieve
	 * @param string $class the class of objects to fetch
	 * @param string $element the top-level JSON element holding the array
	 * @return \OpenCloud\Collection
	 */
	public function Collection($url, $class, $element, $parent=NULL) {
		if (!isset($parent))
			$parent = $this;
		$this->debug('%s:Collection(%s, %s, %s)',
			get_class($this), $url, $class, $element);

		// fetch the list
		$response = $this->Request($url);
		$this->debug('response %d [%s]', 
			$response->HttpStatus(), $response->HttpBody());

		// check return code
		if ($response->HttpStatus() > 204)
			throw new \OpenCloud\CollectionError(sprintf(
				_('Unable to retrieve [%s] list from [%s], '.
				  'status [%d] response [%s]'),
				$class,
				$url,
				$response->HttpStatus(),
				$response->HttpBody()));

		// handle empty response
		if (strlen($response->HttpBody()) == 0)
			return new Collection($parent, $class, array());

		// parse the return
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;

		// and say goodbye
		if (!$element)
			return new Collection($parent, $class, $obj);
		elseif (isset($obj->$element))
			return new Collection($parent, $class, $obj->$element);
		else
			return new Collection($parent, $class, array());
	}

	/********** PRIVATE METHODS **********/

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
    private function get_endpoint($type, $name, $region, $urltype='publicURL') {
        $found = 0;
        $catalog = $this->conn->serviceCatalog();

        // search each service to find The One
        foreach($catalog as $service) {
        	// first, compare the type ("compute") and name ("openstack")
            if ((!strcasecmp($service->type, $type)) &&
                 (!strcasecmp($service->name, $name))) {
                // found the service, now we need to find the region
                foreach($service->endpoints as $endpoint) {
                    // compare the regions
                    if ((!strcasecmp($endpoint->region, $region)) &&
                         isset($endpoint->$urltype)) {
                        // now we have a match! Yay!
                        ++$found;
                        return $endpoint->$urltype;
                    }
                }
            }
        }

        // error if not found
        if (!$found)
            throw new EndpointError(
                sprintf(_('No endpoints for service type [%s], name [%s], '.
                  'region [%s] and urlType [%s]'),
                $type,
                $name,
                $region,
                $urltype)
           );
    } // function get_endpoint()

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
	private function GetMetaUrl($resource) {
		$urlbase = $this->get_endpoint(
			$this->service_type,
			$this->service_name,
			$this->service_region,
			RAXSDK_URL_PUBLIC
		);
		if ($urlbase == '')
			return array();
		$ext_url = noslash($urlbase) .
						'/' .
						$resource;
		$response = $this->Request($ext_url);

		// check for NOT FOUND response
		if ($response->HttpStatus() == 404)
		    return array();

		// check for error status
		if ($response->HttpStatus() >= 300)
		    throw new HttpError(sprintf(
		        _('Error accessing [%s] - status [%d], response [%s]'),
		        $urlbase, $response->HttpStatus(), $response->HttpBody()));

		// we're good; proceed
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;
		return $obj;
	}

} // class Service
