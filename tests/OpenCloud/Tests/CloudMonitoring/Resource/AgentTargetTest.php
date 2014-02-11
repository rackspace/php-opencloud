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

class AgentTargetTest extends CloudMonitoringTestCase
{
    public function setupObjects()
    {
        parent::setupObjects();

        $this->resource = $this->service->resource('AgentTarget', null, $this->entity);
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentTarget',
            $this->resource
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFails()
    {
        $this->resource->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFails()
    {
        $this->resource->update();
    }

    /**
     * @mockFile Agent_Target_List
     */
    public function testCollectionContent()
    {
        $this->resource->setType('agent.filesystem');
        $this->assertEquals('agent.filesystem', $this->resource->getType());

        $targetArray = $this->resource->listAll();

        $this->assertTrue($targetArray->valueExists('/'));
        $this->assertTrue($targetArray->valueExists('/sys/kernel/debug'));
        $this->assertTrue($targetArray->valueExists('/var/lock'));
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testSettingIncorrectTypeFails()
    {
        $this->resource->setType('foobar');
    }
}
