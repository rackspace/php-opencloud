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
use OpenCloud\Common\Http\Message\Formatter;

/**
 * Agent class.
 */
class Agent extends ReadOnlyResource
{
    /**
     * Agent IDs are user specified strings that are a maximum of 255 characters and can contain letters, numbers,
     * dashes and dots.
     *
     * @var string
     */
    private $id;

    /**
     * @var int UTC timestamp of last connection.
     */
    private $last_connected;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'agents';

    /**
     * @return mixed
     * @throws \OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function getConnections()
    {
        if (!$this->getId()) {
            throw new Exception\AgentException(
                'Please specify an "ID" value'
            );
        }

        return $this->getService()->resourceList('AgentConnection', $this->getUrl('connections'));
    }

    /**
     * @param $connectionId
     * @return mixed
     * @throws \OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function getConnection($connectionId)
    {
        if (!$this->getId()) {
            throw new Exception\AgentException(
                'Please specify an "ID" value'
            );
        }

        $url = clone $this->getUrl();
        $url->addPath('connections')->addPath($connectionId);

        $response = $this->getClient()->get($url)->send();
        $body = Formatter::decode($response);

        return $this->getService()->resource('AgentConnection', $body);
    }

    /**
     * Retrieves the agent host object responsible for monitoring certain
     * metrics for an agent.
     *
     * @return AgentHost
     */
    public function getAgentHost()
    {
        return $this->getService()->resource('AgentHost', null, $this);
    }
}
