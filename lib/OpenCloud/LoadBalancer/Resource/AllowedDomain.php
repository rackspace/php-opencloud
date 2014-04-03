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
 * The allowed domains are restrictions set for the allowed domain names used
 * for adding load balancer nodes. In order to submit a domain name as an address
 * for the load balancer node to add, the user must verify that the domain is
 * valid by using the List Allowed Domains call.
 *
 * Note that this is actually a sub-resource of the load balancers service,
 * and not of the load balancer object. It's included here for convenience,
 * since it matches the pattern of the other LB subresources.
 */
class AllowedDomain extends ReadOnlyResource
{
    public $name;

    protected static $json_name = 'allowedDomain';
    protected static $json_collection_name = 'allowedDomains';
    protected static $json_collection_element = 'allowedDomain';
    protected static $url_resource = 'loadbalancers/alloweddomains';
}
