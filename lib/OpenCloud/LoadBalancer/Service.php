<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\LoadBalancer;

use OpenCloud\Common\Service\NovaService;

/**
 * The Rackspace Cloud Load Balancers
 */
class Service extends NovaService
{
    const DEFAULT_NAME = 'cloudLoadBalancers';
    const DEFAULT_TYPE = 'rax:load-balancer';

    /**
     * creates a new LoadBalancer object
     *
     * @api
     * @param string $id the identifier of the load balancer
     * @return Resource\LoadBalancer
     */
    public function loadBalancer($id = null)
    {
        return new Resource\LoadBalancer($this, $id);
    }

    /**
     * returns a Collection of LoadBalancer objects
     *
     * @api
     * @param boolean $detail if TRUE (the default), then all details are
     *                        returned; otherwise, the minimal set (ID, name) are retrieved
     * @param array   $filter if provided, a set of key/value pairs that are
     *                        set as query string parameters to the query
     * @return \OpenCloud\Common\Collection
     */
    public function loadBalancerList($detail = true, $filter = array())
    {
        return $this->collection('OpenCloud\LoadBalancer\Resource\LoadBalancer');
    }

    /**
     * creates a new BillableLoadBalancer object (read-only)
     *
     * @api
     * @param string $id the identifier of the load balancer
     * @return Resource\LoadBalancer
     */
    public function billableLoadBalancer($id = null)
    {
        return new Resource\BillableLoadBalancer($this, $id);
    }

    /**
     * returns a Collection of BillableLoadBalancer objects
     *
     * @api
     * @param boolean $detail if TRUE (the default), then all details are
     *                        returned; otherwise, the minimal set (ID, name) are retrieved
     * @param array   $filter if provided, a set of key/value pairs that are
     *                        set as query string parameters to the query
     * @return \OpenCloud\Common\Collection
     */
    public function billableLoadBalancerList($detail = true, $filter = array())
    {
        $class = 'OpenCloud\LoadBalancer\Resource\BillableLoadBalancer';
        $url = $this->url($class::ResourceName(), $filter);

        return $this->collection($class, $url);
    }

    /**
     * returns allowed domain
     *
     * @api
     * @param mixed $data either an array of values or null
     * @return Resource\AllowedDomain
     */
    public function allowedDomain($data = null)
    {
        return new Resource\AllowedDomain($this, $data);
    }

    /**
     * returns Collection of AllowedDomain object
     *
     * @api
     * @return Collection
     */
    public function allowedDomainList()
    {
        return $this->collection('OpenCloud\LoadBalancer\Resource\AllowedDomain', null, $this);
    }

    /**
     * single protocol (should never be called directly)
     *
     * Convenience method to be used by the ProtocolList Collection.
     *
     * @return Resource\Protocol
     */
    public function protocol($data = null)
    {
        return new Resource\Protocol($this, $data);
    }

    /**
     * a list of Protocol objects
     *
     * @api
     * @return \OpenCloud\Common\Collection
     */
    public function protocolList()
    {
        return $this->collection('OpenCloud\LoadBalancer\Resource\Protocol', null, $this);
    }

    /**
     * single algorithm (should never be called directly)
     *
     * convenience method used by the Collection factory
     *
     * @return Resource\Algorithm
     */
    public function algorithm($data = null)
    {
        return new Resource\Algorithm($this, $data);
    }

    /**
     * a list of Algorithm objects
     *
     * @api
     * @return \OpenCloud\Common\Collection
     */
    public function algorithmList()
    {
        return $this->collection('OpenCloud\LoadBalancer\Resource\Algorithm', null, $this);
    }
}
