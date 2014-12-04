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

namespace OpenCloud\Networking\Resource;

use OpenCloud\Common\Resource\PersistentResource;

/**
 * A subnet represents an IP address block that can be used to assign IP
 * addresses to virtual instances (such as servers created using the {@see
 * \OpenCloud\Compute\Service}. Each subnet must have a CIDR and must be
 * associated with a network.
 *
 * @see http://docs.openstack.org/api/openstack-network/2.0/content/Overview-d1e71.html#Subnet
 *
 * @package OpenCloud\Networking\Resource
 */
class Subnet extends PersistentResource
{
    protected static $url_resource = 'subnets';
    protected static $json_name = 'subnet';

    protected $id;
    protected $name;
    protected $enableDhcp;
    protected $networkId;
    protected $dnsNameservers;
    protected $allocationPools;
    protected $hostRoutes;
    protected $ipVersion;
    protected $gatewayIp;
    protected $cidr;
    protected $tenantId;
    protected $links;

    protected $aliases = array(
        'enable_dhcp'      => 'enableDhcp',
        'network_id'       => 'networkId',
        'dns_nameservers'  => 'dnsNameservers',
        'allocation_pools' => 'allocationPools',
        'host_routes'      => 'hostRoutes',
        'ip_version'       => 'ipVersion',
        'gateway_ip'       => 'gatewayIp',
        'tenant_id'        => 'tenantId'
    );

    protected $createKeys = array(
        'name',
        'enableDhcp',
        'networkId',
        'allocationPools',
        'hostRoutes',
        'ipVersion',
        'gatewayIp',
        'cidr',
        'tenantId'
    );

    protected $updateKeys = array(
        'name',
        'enableDhcp',
        'hostRoutes',
        'gatewayIp'
    );

    /**
     * This method is inherited. The inherited method has protected scope
     * but we are widening the scope to public so this method may be called
     * from other classes such as {@see OpenCloud\Networking\Service}.
     */
    public function createJson()
    {
        return parent::createJson();
    }
}
