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
 * Active health monitoring is a technique that uses synthetic transactions
 * executed at periodic intervals to determine the condition of a node. One of
 * the advantages of active health monitoring is that it does not require active
 * transactions to be processed by the load balancer to determine whether or not
 * a node is suitable for handling traffic. Active health monitoring is not
 * applied by default and must be enabled per load balancer.
 *
 * The active health monitor can use one of three types of probes:
 *
 * * connect
 * * HTTP
 * * HTTPS
 *
 * These probes are executed at configured intervals; in the event of a failure,
 * the node status changes to OFFLINE and the node will not receive traffic. If,
 * after running a subsequent test, the probe detects that the node has recovered,
 * then the node's status is changed to ONLINE and it is capable of servicing requests.
 */
class HealthMonitor extends NonIdUriResource
{
    /**
     * Type of the health monitor. Can either be "connect", "HTTP" or "HTTPS"
     *
     * @var string
     */
    public $type;

    /**
     * The minimum number of seconds to wait before executing the health monitor.
     * Must be a number between 1 and 3600.
     *
     * @var int
     */
    public $delay;

    /**
     * Maximum number of seconds to wait for a connection to be established
     * before timing out. Must be a number between 1 and 300.
     *
     * @var int
     */
    public $timeout;

    /**
     * Number of permissible monitor failures before removing a node from rotation.
     * Must be a number between 1 and 10.
     *
     * @var int
     */
    public $attemptsBeforeDeactivation;

    /**
     * A regular expression that will be used to evaluate the contents of the
     * body of the response.
     *
     * @var string
     */
    public $bodyRegex;

    /**
     * The name of a host for which the health monitors will check.
     *
     * @var string
     */
    public $hostHeader;

    /**
     * The HTTP path that will be used in the sample request.
     *
     * @var string
     */
    public $path;

    /**
     * A regular expression that will be used to evaluate the HTTP status code
     * returned in the response.
     *
     * @var string
     */
    public $statusRegex;

    protected static $json_name = 'healthMonitor';
    protected static $url_resource = 'healthmonitor';

    protected $createKeys = array(
        'type',
        'delay',
        'timeout',
        'attemptsBeforeDeactivation',
        'bodyRegex',
        'hostHeader',
        'path',
        'statusRegex'
    );

    public function create($params = array())
    {
        return $this->update($params);
    }
}
