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

namespace OpenCloud\Tests\Orchestration\Resource;

use OpenCloud\Tests\Orchestration\OrchestrationTestCase;

class ResourceTest extends OrchestrationTestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Resource', $this->resource);
    }

    public function testGetResourceName()
    {
        $this->assertEquals('my_instance', $this->resource->getResourceName());
    }

    public function testGetLogicalResourceId()
    {
        $this->assertEquals('my_instance', $this->resource->getLogicalResourceId());
    }

    public function testGetPhysicalResourceId()
    {
        $this->assertEquals('7fdb0a8a-6514-455c-b5cf-96a131b71352', $this->resource->getPhysicalResourceId());
    }

    public function testGetResourceStatus()
    {
        $this->assertEquals('CREATE_COMPLETE', $this->resource->getResourceStatus());
    }

    public function testGetResourceStatusReason()
    {
        $this->assertEquals('state changed', $this->resource->getResourceStatusReason());
    }

    public function testGetResourceType()
    {
        $this->assertEquals('OS::Nova::Server', $this->resource->getResourceType());
    }

    public function testGetUpdatedTime()
    {
        $this->assertEquals('2014-03-20T14:27:15Z', $this->resource->getUpdatedTime());
    }

    public function testGetRequiredBy()
    {
        $requiredBy = $this->resource->getRequiredBy();
        $this->assertTrue(is_array($requiredBy));
        $this->assertEquals(0, count($requiredBy));
    }

    public function testGetLinks()
    {
        $links = $this->resource->getLinks();
        $this->assertTrue(is_array($links));
        $this->assertEquals(2, count($links));
    }

}
