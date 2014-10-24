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

use OpenCloud\CloudMonitoring\Exception;

/**
 * Agent class.
 */
class AgentTarget extends ReadOnlyResource
{
    private $type = 'agent.filesystem';

    protected static $json_name = 'targets';
    protected static $json_collection_name = 'targets';
    protected static $url_resource = 'targets';

    protected $allowedTypes = array(
        'agent.filesystem',
        'agent.memory',
        'agent.load_average',
        'agent.cpu',
        'agent.disk',
        'agent.network',
        'agent.plugin'
    );

    public function getUrl($path = null, array $query = array())
    {
        $path = "agent/check_types/{$this->type}/{$this->resourceName()}";

        return $this->getParent()->getUrl($path);
    }

    public function setType($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new Exception\AgentException(sprintf(
                'Incorrect target type. Please specify one of the following: %s',
                implode(', ', $this->allowedTypes)
            ));
        }

        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
