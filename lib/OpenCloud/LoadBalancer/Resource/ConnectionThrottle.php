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
 * The connection throttling feature imposes limits on the number of connections
 * per IP address to help mitigate malicious or abusive traffic to your
 * applications. The attributes in the table that follows can be configured
 * based on the traffic patterns for your sites.
 */
class ConnectionThrottle extends NonIdUriResource
{
    /**
     * Allow at least this number of connections per IP address before applying
     * throttling restrictions. Setting a value of 0 allows unlimited
     * simultaneous connections; otherwise, set a value between 1 and 1000.
     *
     * @var int
     */
    public $minConnections;

    /**
     * Maximum number of connections to allow for a single IP address. Setting a
     * value of 0 will allow unlimited simultaneous connections; otherwise set a
     * value between 1 and 100000.
     *
     * @var int
     */
    public $maxConnections;

    /**
     * Maximum number of connections allowed from a single IP address in the
     * defined rateInterval. Setting a value of 0 allows an unlimited connection
     * rate; otherwise, set a value between 1 and 100000.
     *
     * @var int
     */
    public $maxConnectionRate;

    /**
     * Frequency (in seconds) at which the maxConnectionRate is assessed.
     * For example, a maxConnectionRate of 30 with a rateInterval of 60 would
     * allow a maximum of 30 connections per minute for a single IP address.
     * This value must be between 1 and 3600.
     *
     * @var int
     */
    public $rateInterval;

    protected static $json_name = "connectionThrottle";
    protected static $url_resource = "connectionthrottle";

    protected $createKeys = array(
        'minConnections',
        'maxConnections',
        'maxConnectionRate',
        'rateInterval'
    );

    public function create($params = array())
    {
        return $this->update($params);
    }
}
