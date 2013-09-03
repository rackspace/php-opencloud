<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\LoadBalancer;

use OpenCloud\Common\Nova;
use OpenCloud\OpenStack;

/**
 * The Rackspace Cloud Load Balancers
 */
class Service extends Nova
{

    const SERVICE_TYPE = 'rax:load-balancer';
    const SERVICE_OBJECT_CLASS = 'LoadBalancer';
    const URL_RESOURCE = 'loadbalancers';
    const JSON_ELEMENT = 'loadBalancers';

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
    public function __construct(OpenStack $conn, $name, $region, $urltype) 
    {
        parent::__construct($conn, self::SERVICE_TYPE, $name, $region, $urltype);
    }

    /**
     * Returns the URL of this service, or optionally that of
     * an instance
     *
     * @param string $resource the resource required
     * @param array $args extra arguments to pass to the URL as query strings
     */
    public function url($resource = self::URL_RESOURCE, array $args = array()) 
    {
        return parent::url($resource, $args);
    }

    /**
     * creates a new LoadBalancer object
     *
     * @api
     * @param string $id the identifier of the load balancer
     * @return LoadBalancerService\LoadBalancer
     */
    public function loadBalancer($id = null) 
    {
        return new Resources\LoadBalancer($this, $id);
    }

    /**
     * returns a Collection of LoadBalancer objects
     *
     * @api
     * @param boolean $detail if TRUE (the default), then all details are
     *      returned; otherwise, the minimal set (ID, name) are retrieved
     * @param array $filter if provided, a set of key/value pairs that are
     *      set as query string parameters to the query
     * @return \OpenCloud\Collection
     */
    public function loadBalancerList($detail = true, $filter = array()) 
    {
        return $this->collection('OpenCloud\LoadBalancer\Resources\LoadBalancer');
    }

    /**
     * creates a new BillableLoadBalancer object (read-only)
     *
     * @api
     * @param string $id the identifier of the load balancer
     * @return LoadBalancerService\LoadBalancer
     */
    public function billableLoadBalancer($id = null) 
    {
        return new Resources\BillableLoadBalancer($this, $id);
    }

    /**
     * returns a Collection of BillableLoadBalancer objects
     *
     * @api
     * @param boolean $detail if TRUE (the default), then all details are
     *      returned; otherwise, the minimal set (ID, name) are retrieved
     * @param array $filter if provided, a set of key/value pairs that are
     *      set as query string parameters to the query
     * @return \OpenCloud\Collection
     */
    public function billableLoadBalancerList($detail = true, $filter = array()) 
    {
        $class = 'OpenCloud\LoadBalancer\Resources\BillableLoadBalancer';
        $url = $this->url($class::ResourceName(), $filter);
        return $this->collection($class, $url);
    }

    /**
     * returns allowed domain
     *
     * @api
     * @param mixed $data either an array of values or null
     * @return LoadBalancerService\AllowedDomain
     */
    public function allowedDomain($data = null) 
    {
        return new Resources\AllowedDomain($this, $data);
    }

    /**
     * returns Collection of AllowedDomain object
     *
     * @api
     * @return Collection
     */
    public function allowedDomainList() 
    {
        return $this->collection('OpenCloud\LoadBalancer\Resources\AllowedDomain', null, $this);
    }

    /**
     * single protocol (should never be called directly)
     *
     * Convenience method to be used by the ProtocolList Collection.
     *
     * @return LoadBalancerService\Protocol
     */
    public function protocol($data = null) 
    {
        return new Resources\Protocol($this, $data);
    }

    /**
     * a list of Protocol objects
     *
     * @api
     * @return Collection
     */
    public function protocolList() 
    {
        return $this->collection('OpenCloud\LoadBalancer\Resources\Protocol', null, $this);
    }

    /**
     * single algorithm (should never be called directly)
     *
     * convenience method used by the Collection factory
     *
     * @return LoadBalancerService\Algorithm
     */
    public function algorithm($data = null) 
    {
        return new Resources\Algorithm($this, $data);
    }

    /**
     * a list of Algorithm objects
     *
     * @api
     * @return Collection
     */
    public function algorithmList() 
    {
        return $this->collection('OpenCloud\LoadBalancer\Resources\Algorithm', null, $this);
    }

}
