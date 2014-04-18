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
 * All load balancers must define the protocol of the service which is being
 * load balanced. The protocol selection should be based on the protocol of the
 * back-end nodes. When configuring a load balancer, the default port for the
 * given protocol will be selected unless otherwise specified.
 *
 * @link http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/List_Load_Balancing_Protocols-d1e4269.html
 */
class Protocol extends ReadOnlyResource
{
    public $name;
    public $port;

    protected static $json_name = 'protocol';
    protected static $url_resource = 'loadbalancers/protocols';
}
