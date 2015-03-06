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
 * A port represents a virtual switch port on a logical network switch, represented
 * by {@see \OpenCloud\Networking\Resource\Network}. Virtual instances (such as
 * servers created using the {@see \OpenCloud\Compute\Service}) attach their
 * interfaces into ports. The port also defines the MAC address and the IP
 * address(es) to be assigned to the interfaces plugged into them. When IP addresses
 * are associated to a port, this also implies the port is associated with a {@see
 * \OpenCloud\Networking\Resource\Subnet}, as the IP address is taken from the
 * allocation pool for a specific subnet.
 *
 * @see http://docs.openstack.org/api/openstack-network/2.0/content/Overview-d1e71.html#Port
 *
 * @package OpenCloud\Networking\Resource
 */
class Port extends PersistentResource
{
    protected static $url_resource = 'ports';
    protected static $json_name = 'port';

    protected $id;
    protected $name;
    protected $adminStateUp;
    protected $networkId;
    protected $deviceId;
    protected $deviceOwner;
    protected $fixedIps;
    protected $macAddress;
    protected $securityGroups;
    protected $status;
    protected $tenantId;
    protected $links;

    protected $aliases = array(
        'admin_state_up'   => 'adminStateUp',
        'network_id'       => 'networkId',
        'device_id'        => 'deviceId',
        'device_owner'     => 'deviceOwner',
        'fixed_ips'        => 'fixedIps',
        'mac_address'      => 'macAddress',
        'security_groups'  => 'securityGroups',
        'tenant_id'        => 'tenantId',
        'subnet_id'        => 'subnetId',
        'ip_address'       => 'ipAddress'
    );

    protected $createKeys = array(
        'name',
        'adminStateUp',
        'networkId',
        'deviceId',
        'deviceOwner',
        'fixedIps',
        'macAddress',
        'securityGroups',
        'tenantId'
    );

    protected $updateKeys = array(
        'name',
        'deviceId'
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
