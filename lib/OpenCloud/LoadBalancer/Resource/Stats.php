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
 * Returns statistics about the load balancer.
 *
 * @link http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/List_Load_Balancer_Stats-d1e1524.html
 */
class Stats extends ReadOnlyResource
{
    /**
     * Connections closed by this load balancer because the 'connect_timeout'
     * interval was exceeded.
     *
     * @var int
     */
    public $connectTimeOut;

    /**
     * Number of transaction or protocol errors in this load balancer.
     *
     * @var int
     */
    public $connectError;

    /**
     * Number of connection failures in this load balancer.
     *
     * @var int
     */
    public $connectFailure;

    /**
     * Connections closed by this load balancer because the 'timeout' interval
     * was exceeded.
     *
     * @var int
     */
    public $dataTimedOut;

    /**
     * Connections closed by this load balancer because the 'keepalive_timeout'
     * interval was exceeded.
     *
     * @var int
     */
    public $keepAliveTimedOut;

    /**
     * Maximum number of simultaneous TCP connections this load balancer has
     * processed at any one time.
     *
     * @var int
     */
    public $maxConn;

    protected static $json_name = false;
    protected static $url_resource = 'stats';

    public function refresh($id = null, $url = null)
    {
        return $this->refreshFromParent();
    }
}
