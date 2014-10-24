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

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Collection\MonitoringIterator;
use OpenCloud\CloudMonitoring\Exception;

/**
 * Agent class.
 */
class AgentHost extends ReadOnlyResource
{
    private $token;
    private $label;

    protected static $json_name = false;
    protected static $json_collection_name = 'info';
    protected static $url_resource = 'host_info';

    private $allowedTypes = array(
        'cpus',
        'disks',
        'filesystems',
        'memory',
        'network_interfaces',
        'processes',
        'system',
        'who'
    );

    public function info($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new Exception\AgentException(sprintf(
                'Incorrect info type. Please specify one of the following: %s',
                implode(', ', $this->allowedTypes)
            ));
        }

        return MonitoringIterator::factory($this, array(
            'baseUrl'       => $this->getUrl($type),
            'resourceClass' => 'AgentHostInfo'
        ));
    }
}
