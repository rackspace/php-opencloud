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
 * A network is an isolated virtual layer-2 broadcast domain that is typically
 * reserved for the tenant who created it unless you configure the network to be
 * shared. The network is the main entity in the Networking service. Ports ({@see
 * \OpenCloud\Networking\Resource\Port}) and subnets ({@see
 * \OpenCloud\Networking\Resource\Subnet}) are always associated with a network.
 *
 * @see http://docs.openstack.org/api/openstack-network/2.0/content/Overview-d1e71.html#Network
 *
 * @package OpenCloud\Networking\Resource
 */
class Network extends PersistentResource implements NetworkInterface
{
    protected static $url_resource = 'networks';
    protected static $json_name = 'network';

    protected $id;
    protected $adminStateUp;
    protected $name;
    protected $shared;
    protected $status;
    protected $subnets;
    protected $tenantId;
    protected $links;

    protected $aliases = array(
        'admin_state_up' => 'adminStateUp',
        'tenant_id'      => 'tenantId'
    );

    protected $createKeys = array(
        'adminStateUp',
        'name',
        'shared',
        'tenantId'
    );

    protected $updateKeys = array(
        'name'
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

    public function getId()
    {
        return $this->id;
    }
}
