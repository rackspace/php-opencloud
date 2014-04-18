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

namespace OpenCloud\LoadBalancer\Resource;

/**
 * A virtual IP (VIP) makes a load balancer accessible by clients. The load
 * balancing service supports either a public VIP, routable on the public
 * Internet, or a ServiceNet address, routable only within the region in which
 * the load balancer resides.
 */
class VirtualIp extends NonIdUriResource
{
    public $id;

    /**
     * IP address.
     *
     * @var string
     */
    public $address;

    /**
     * Either "PUBLIC" (public Internet) or "SERVICENET" (internal Rackspace network)
     *
     * @var int
     */
    public $type;

    /**
     * Either 4 or 6.
     *
     * @var int
     */
    public $ipVersion;

    protected static $json_collection_name = 'virtualIps';
    protected static $json_name = false;
    protected static $url_resource = 'virtualips';

    public $createKeys = array(
        'type',
        'ipVersion'
    );

    public function update($params = array())
    {
        return $this->noUpdate();
    }
}
