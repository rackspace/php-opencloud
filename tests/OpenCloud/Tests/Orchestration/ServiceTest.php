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

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version   1.0.0
 * @author    Shaunak Kashyap <shaunak.kashyap@rackspace.com>
 */

namespace OpenCloud\Tests\Orchestration;

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;

    public function setupObjects()
    {
        $this->service = $this->getClient()->orchestrationService('cloudOrchestration', 'DFW', 'publicURL');
    }

    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Orchestration\Service',
            $this->service
        );
    }

    public function testStack()
    {
        $this->assertInstanceOf(
            'OpenCloud\Orchestration\Resource\Stack',
            $this->service->stack()
        );
    }

    public function testStackList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->stackList()
        );
    }

    public function testResourceTypeList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->resourceTypeList()
        );
    }

    public function testResourceType()
    {
        $this->assertInstanceOf(
            'OpenCloud\Orchestration\Resource\ResourceType',
            $this->service->resourceType('dummy')
        );
    }

    public function testBuildInfo()
    {
        $this->assertInstanceOf(
            'OpenCloud\Orchestration\Resource\BuildInfo',
            $this->service->buildInfo()
        );
    }

    // TODO: Validate template
    
}
