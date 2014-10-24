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

use OpenCloud\Tests\OpenCloudTestCase;

class AgentTokenTest extends OpenCloudTestCase
{
    const TOKEN_ID = 'someId';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('AgentToken');
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Agenttoken',
            $this->resource
        );
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/agent_tokens',
            (string)$this->resource->getUrl()
        );
    }

    public function testCollection()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->resource->listAll());
    }

    public function testGet()
    {
        $this->resource->refresh(self::TOKEN_ID);

        $this->assertEquals($this->resource->getId(), self::TOKEN_ID);
    }
}
