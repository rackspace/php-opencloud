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
 * The access list management feature allows fine-grained network access
 * controls to be applied to the load balancer's virtual IP address. A single IP
 * address, multiple IP addresses, or entire network subnets can be added as a
 * networkItem. Items that are configured with the ALLOW type will always take
 * precedence over items with the DENY type. To reject traffic from all items
 * except for those with the ALLOW type, add a networkItem with an address of
 * "0.0.0.0/0" and a DENY type.
 */
class Access extends NonIdUriResource
{
    public $id;

    /**
     * Type of item to add:
     * ALLOW - Specifies items that will always take precedence over items with
     *  the DENY type.
     * DENY - Specifies items to which traffic can be denied.
     *
     * @var string
     */
    public $type;

    /**
     * IP address for item to add to access list.
     *
     * @var string
     */
    public $address;

    protected static $json_name = "accessList";
    protected static $json_collection_name = "accessList";
    protected static $url_resource = "accesslist";

    protected $createKeys = array(
        'type',
        'address'
    );

    public function update($params = array())
    {
        return $this->noUpdate();
    }
}
