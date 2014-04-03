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
 * Reports all usage for a Load Balancer recorded within the preceding 24 hours.
 */
class UsageRecord extends ReadOnlyResource
{
    public $id;
    public $averageNumConnections;

    /**
     * Incoming transfer in bytes.
     *
     * @var int
     */
    public $incomingTransfer;

    /**
     * Outgoing transfer in bytes.
     *
     * @var int
     */
    public $outgoingTransfer;
    public $averageNumConnectionsSsl;
    public $incomingTransferSsl;
    public $outgoingTransferSsl;
    public $numVips;
    public $numPolls;
    public $startTime;
    public $endTime;
    public $vipType;
    public $sslMode;
    public $eventType;

    protected static $json_name = 'loadBalancerUsageRecord';
    protected static $url_resource = 'usage';

    public function refresh($id = null, $url = null)
    {
        return $this->refreshFromParent();
    }
}
