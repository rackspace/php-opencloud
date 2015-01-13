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

use OpenCloud\Common\Log\Logger;
use OpenCloud\Common\Service\NovaService;
use OpenCloud\LoadBalancer\Collection\LoadBalancerIterator;

/**
 * Class that encapsulates the Rackspace Cloud Load Balancers service
 *
 * @package OpenCloud\LoadBalancer
 */
class Service extends NovaService
{
    const DEFAULT_NAME = 'cloudLoadBalancers';
    const DEFAULT_TYPE = 'rax:load-balancer';

    /**
     * Return a Load Balancer
     *
     * @param string $id
     * @return \OpenCloud\LoadBalancer\Resource\LoadBalancer
     */
    public function loadBalancer($id = null)
    {
        return $this->resource('LoadBalancer', $id);
    }

    /**
     * Return a paginated collection of load balancers
     *
     * @param bool $detail If TRUE, all details are returned; otherwise, a
     *                     minimal set (ID, name) is retrieved [DEPRECATED]
     * @param array $filter Optional query params used for search
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function loadBalancerList($detail = true, array $filter = array())
    {
        $options = $this->makeResourceIteratorOptions($this->resolveResourceClass('LoadBalancer'));

        if (isset($filter['limit'])) {
            $options['limit.page'] = $filter['limit'];
            unset($filter['limit']);
        }

        $url = $this->getUrl();
        $url->addPath(Resource\LoadBalancer::resourceName());
        $url->setQuery($filter);

        $options = array_merge($options, array('baseUrl' => $url, 'key.marker' => 'id'));

        return LoadBalancerIterator::factory($this, $options);
    }

    /**
     * @deprecated
     */
    public function billableLoadBalancer($id = null)
    {
        $this->getLogger()->warning(Logger::deprecated(__METHOD__, 'loadBalancer'));

        return $this->resource('LoadBalancer', $id);
    }

    /**
     * Returns a paginated collection of load balancers that have been billed
     * between a certain period.
     *
     * @link http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/List_Usage-d1e3014.html
     * @param array $filter
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function billableLoadBalancerList(array $filter = array())
    {
        $url = $this->getUrl();
        $url->addPath(Resource\LoadBalancer::resourceName());
        $url->addPath('billable');
        $url->setQuery($filter);

        return $this->resourceList('LoadBalancer', $url);
    }

    /**
     * Returns an allowed domain
     *
     * @param mixed $data either an array of values or null
     * @return \OpenCloud\LoadBalancer\Resource\AllowedDomain
     */
    public function allowedDomain($data = null)
    {
        return $this->resource('AllowedDomain', $data);
    }

    /**
     * Returns Collection of AllowedDomain object
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function allowedDomainList()
    {
        return $this->resourceList('AllowedDomain');
    }

    /**
     * single protocol (should never be called directly)
     *
     * Convenience method to be used by the ProtocolList Collection.
     *
     * @return \OpenCloud\LoadBalancer\Resource\Protocol
     */
    public function protocol($data = null)
    {
        return $this->resource('Protocol', $data);
    }

    /**
     * Returns a list of Protocol objects
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function protocolList()
    {
        return $this->resourceList('Protocol');
    }

    /**
     * single algorithm (should never be called directly)
     *
     * convenience method used by the Collection factory
     *
     * @return \OpenCloud\LoadBalancer\Resource\Algorithm
     */
    public function algorithm($data = null)
    {
        return $this->resource('Algorithm', $data);
    }

    /**
     * Return a list of Algorithm objects
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function algorithmList()
    {
        return $this->resourceList('Algorithm');
    }
}
