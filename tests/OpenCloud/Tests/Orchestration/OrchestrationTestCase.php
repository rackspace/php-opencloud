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

namespace OpenCloud\Tests\Orchestration;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\OpenCloudTestCase;

class OrchestrationTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $buildInfo;
    protected $resourceType;
    protected $stack;
    protected $resource;

    public function setupObjects()
    {
        $this->service = $this->getClient()->orchestrationService();

        $this->addMockSubscriber($this->makeResponse('{"engine":{"revision":"2014.j3-20141003-1139"},"fusion-api":{"revision":"j1-20140915-10d9ee4-98"},"api":{"revision":"2014.j3-20141003-1139"}}'));
        $this->buildInfo = $this->service->getBuildInfo();

        $this->addMockSubscriber($this->makeResponse('{"attributes":{"an_attribute":{"description":"An attribute description ."}},"properties":{"a_property":{"update_allowed":false,"required":true,"type":"string","description":"A resource description."}},"resource_type":"OS::Nova::Server"}'));
        $this->resourceType = $this->service->getResourceType("OS::Nova::Server");

        $this->addMockSubscriber($this->makeResponse('{"stack":{"capabilities":[],"creation_time":"2014-06-03T20:59:46Z","description":"sample stack","disable_rollback":"True","id":"3095aefc-09fb-4bc7-b1f0-f21a304e864c","links":[{"href":"http://192.168.123.200:8004/v1/eb1c63a4f77141548385f113a28f0f52/stacks/simple_stack/3095aefc-09fb-4bc7-b1f0-f21a304e864c","rel":"self"}],"notification_topics":[],"outputs":[],"parameters":{"OS::stack_id":"3095aefc-09fb-4bc7-b1f0-f21a304e864c","OS::stack_name":"simple_stack"},"stack_name":"simple_stack","stack_status":"CREATE_COMPLETE","stack_status_reason":"Stack CREATE completed successfully","template_description":"sample stack","timeout_mins":"","updated_time":""}}'));
        $this->stack = $this->service->getStack('simple_stack');

        $this->addMockSubscriber($this->makeResponse('{"resource":{"resource_name":"MySqlCloudDatabaseServer","description":"","links":[{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxxx21-9xx9-4xxxe-bxxf-a7fxxxxx68/resources/MySqlCloudDatabaseServer","rel":"self"},{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxxx1-9xx9-4xxe-bxxf-a7fxxxxxx68","rel":"stack"}],"logical_resource_id":"MySqlCloudDatabaseServer","resource_status":"CREATE_COMPLETE","updated_time":"2014-02-05T19:20:31Z","required_by":[],"resource_status_reason":"state changed","physical_resource_id":"98xxx0-cxx8-4xxe-bxx5-3fxxxx11","resource_type":"OS::Trove::Instance"}}'));
        $this->resource = $this->stack->getResource('MySqlCloudDatabaseServer');
    }

    protected function assertIsService($object)
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Service', $object);
    }

    protected function assertIsBuildInfo($object)
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\BuildInfo', $object);
    }

    protected function assertIsEvent($object)
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Event', $object);
    }

    protected function assertIsResource($object)
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Resource', $object);
    }

    protected function assertIsResourceType($object)
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\ResourceType', $object);
    }

    protected function assertIsStack($object)
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Stack', $object);
    }
}
