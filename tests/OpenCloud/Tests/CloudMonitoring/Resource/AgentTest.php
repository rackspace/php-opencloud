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

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class AgentTest extends CloudMonitoringTestCase
{
    const AGENT_ID = '00-agent.example.com';
    const CONNECTION_ID = 'cntl4qsIbA';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();
        $this->resource = $this->service->getAgent();
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Agent',
            $this->resource
        );
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/agents',
            (string)$this->resource->getUrl()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithNoParams()
    {
        $this->resource->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFailsWithNoParams()
    {
        $this->resource->update();
    }

    /**
     * @mockFile Agent_List
     */
    public function testCollection()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->resource->listAll()
        );
    }

    /**
     * @mockFile Agent
     */
    public function testGet()
    {
        $this->resource->refresh(self::AGENT_ID);
        $this->assertEquals($this->resource->getId(), '1d29b4f9-87ca-4b22-b5a2-4e40a07bf068');
        $this->assertEquals($this->resource->getLastConnected(), 1383616760309);
    }

    /**
     * @mockFile Agent_Connection_List
     */
    public function testGetConnections()
    {
        $this->resource->setId(self::AGENT_ID);
        $list = $this->resource->getConnections();

        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);

        $first = $list->first();

        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentConnection',
            $first
        );

        $this->assertEquals('cntl4qsIbA', $first->getId());
        $this->assertEquals('0b49b96d-24c9-45ca-c585-4040707f4880', $first->getGuid());
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testGetConnectionsFailsWithoutId()
    {
        $this->resource->setId(null);
        $this->resource->getConnections();
    }

    /**
     * @mockFile Agent_Connection
     */
    public function testGetConnection()
    {
        $this->resource->setId(self::AGENT_ID);
        $connection = $this->resource->getConnection(self::CONNECTION_ID);

        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentConnection',
            $connection
        );
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testGetConnectionFailsWithoutId()
    {
        $this->resource->setId(null);
        $this->resource->getConnection(null);
    }

    public function testGettingHostReturnsInstance()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentHost',
            $this->resource->getAgentHost()
        );
    }
}
