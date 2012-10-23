<?php
/**
 * Rackspace's Cloud Load Balancers
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

require_once('nova.inc');
require_once('loadbalancer.inc');

/**
 * The Rackspace Cloud Load Balancers
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class LoadBalancerService extends Nova {

    const
        SERVICE_TYPE = 'rax:load-balancer',
        SERVICE_OBJECT_CLASS = 'LoadBalancer',
        URL_RESOURCE = 'loadbalancers',
        JSON_ELEMENT = 'loadBalancers';

	/**
	 * Creates a new LoadBalancerService connection
	 *
	 * This is not normally called directly, but via the factory method on the
	 * OpenStack or Rackspace connection object.
	 *
	 * @param OpenStack $conn the connection on which to create the service
	 * @param string $name the name of the service (e.g., "cloudDatabases")
	 * @param string $region the region of the service (e.g., "DFW" or "LON")
	 * @param string $urltype the type of URL (normally "publicURL")
	 */
	public function __construct(OpenStack $conn, $name, $region, $urltype) {
		parent::__construct($conn, self::SERVICE_TYPE,
		    $name, $region, $urltype);
	}

	/**
	 * Returns the URL of this service, or optionally that of
	 * an instance
	 *
	 * @param string $resource the resource required
	 * @param array $args extra arguments to pass to the URL as query strings
	 */
	public function Url($resource=self::URL_RESOURCE, $args=array()) {
		return parent::Url($resource, $args);
	}

	/**
	 * creates a new LoadBalancer object
	 *
	 * @api
	 * @param string $id the identifier of the load balancer
	 * @return LoadBalancerService\LoadBalancer
	 */
	public function LoadBalancer($id=NULL) {
		return new LoadBalancerService\LoadBalancer($this, $id);
	}

	/**
	 * returns a Collection of LoadBalancer objects
	 *
	 * @api
	 * @param boolean $detail if TRUE (the default), then all details are
	 *      returned; otherwise, the minimal set (ID, name) are retrieved
	 * @param array $filter not currently used; to support potential future
	 *      server-side filtering
	 * @return \OpenCloud\Collection
	 */
	public function LoadBalancerList($detail=TRUE, $filter=array()) {
		return $this->Collection(
			$this->Url(),
			self::SERVICE_OBJECT_CLASS,
			self::JSON_ELEMENT);
	}

}
