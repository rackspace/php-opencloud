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

use OpenCloud\Orchestration\Service;
use OpenCloud\Common\Exceptions\InvalidTemplateError;

class ServiceTest extends OrchestrationTestCase
{
    public function test__construct()
    {
        $service = $this->getClient()->orchestrationService(null, 'DFW');
        $this->assertIsService($service);
    }

    public function testPreviewStack()
    {
        $this->assertIsStack($this->service->previewStack());
    }

    public function testCreateStack()
    {
        $this->assertIsStack($this->service->createStack());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAdoptStackWithoutAdoptStackData()
    {
        $this->service->adoptStack();
    }

    public function testAdoptStackWithAdoptStackData()
    {
        $this->assertIsStack($this->service->adoptStack(array('adoptStackData' => 'foobar')));
    }

    public function testListStacks()
    {
        $this->addMockSubscriber($this->makeResponse('{"stacks":[{"description":"HEAT template for deploying a multi-node wordpress deployment on Rackspace Cloud\nusing Cloud Servers, Cloud Load Balancers and Cloud Databases. This version uses\na user-defined template resource to specify the implementation of the web-heads\n","links":[{"href":"http:\/\/xxxxx\/v1\/xxxx\/stacks\/xxxx\/11cd9b5e-c7ff-43b5-bff8-b0e7429cd87e","rel":"self"}],"stack_status_reason":"Resource suspend failed: Error: State invalid for suspend","stack_name":"timswp6","creation_time":"2014-01-30T20:47:57Z","updated_time":"2014-02-03T18:04:39Z","stack_status":"SUSPEND_FAILED","id":"11cd9b5e-c7ff-43b5-bff8-b0e7429cd87e"},{"description":"HEAT template for deploying a multi-node wordpress deployment on Rackspace Cloud\nusing Cloud Servers, Cloud Load Balancers and Cloud Databases. This version uses\na user-defined template resource to specify the implementation of the web-heads\n","links":[{"href":"http:\/\/xxxx\/v1\/xxxx\/stacks\/xxxx\/1b2ed5de-9b8c-43fa-9392-1da17b5dee7c","rel":"self"}],"stack_status_reason":"Stack create completed successfully","stack_name":"timswp5","creation_time":"2014-01-30T18:18:12Z","updated_time":"2014-01-30T18:42:07Z","stack_status":"CREATE_COMPLETE","id":"1b2ed5de-9b8c-43fa-9392-1da17b5dee7c"}]}'));

        $stacks = $this->service->listStacks();
        $this->isCollection($stacks);
        $this->assertIsStack($stacks->getElement(0));
    }

    public function testGetStackByName()
    {
        $this->addMockSubscriber($this->makeResponse('{"stack":{"disable_rollback":true,"description":"MYSQL server cloud database instance running on Rackspace cloud","parameters":{"instance_name":"testdb","OS::stack_id":"87xxx21-9db9-49be-bc4f-a7f2exxxx68","OS::stack_name":"trove2"},"stack_status_reason":"Stack create completed successfully","stack_name":"trove2","outputs":[],"creation_time":"2014-02-05T19:18:56Z","links":[{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxx21-9db9-49be-bc4f-a7f2exxxx68","rel":"self"}],"capabilities":[],"notification_topics":[],"timeout_mins":60,"stack_status":"CREATE_COMPLETE","updated_time":"2014-02-05T19:20:31Z","id":"879xxx21-9db9-49be-bc4f-a7f2exxxx9068","template_description":"MYSQL server cloud database instance running on Rackspace cloud"}}'));

        $stack = $this->service->getStack('foobar');
        $this->assertIsStack($stack);
        $this->assertEquals('MYSQL server cloud database instance running on Rackspace cloud', $stack->getDescription());
    }

    public function testGetStackByNameAndId()
    {
        $this->addMockSubscriber($this->makeResponse('{"stack":{"disable_rollback":true,"description":"MYSQL server cloud database instance running on Rackspace cloud","parameters":{"instance_name":"testdb","OS::stack_id":"87xxx21-9db9-49be-bc4f-a7f2exxxx68","OS::stack_name":"trove2"},"stack_status_reason":"Stack create completed successfully","stack_name":"trove2","outputs":[],"creation_time":"2014-02-05T19:18:56Z","links":[{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxx21-9db9-49be-bc4f-a7f2exxxx68","rel":"self"}],"capabilities":[],"notification_topics":[],"timeout_mins":60,"stack_status":"CREATE_COMPLETE","updated_time":"2014-02-05T19:20:31Z","id":"879xxx21-9db9-49be-bc4f-a7f2exxxx9068","template_description":"MYSQL server cloud database instance running on Rackspace cloud"}}'));

        $stack = $this->service->getStack('foobar', 1234);
        $this->assertIsStack($stack);
        $this->assertEquals('MYSQL server cloud database instance running on Rackspace cloud', $stack->getDescription());
    }

    public function testListResourceTypes()
    {
        $this->addMockSubscriber($this->makeResponse('{"resource_types":["OS::Nova::Server","OS::Heat::RandomString","OS::Swift::Container","Rackspace::Cloud::Server","OS::Heat::ChefSolo","Rackspace::AutoScale::WebHook","Rackspace::AutoScale::Group","Rackspace::Cloud::Network","OS::Cinder::Volume","Rackspace::Cloud::WinServer","Rackspace::Cloud::LoadBalancer","OS::Heat::ResourceGroup","Rackspace::AutoScale::ScalingPolicy","Rackspace::Cloud::DNS","OS::Trove::Instance","OS::Nova::FloatingIPAssociation","OS::Cinder::VolumeAttachment","OS::Nova::FloatingIP","OS::Nova::KeyPair"]}'));

        $resourceTypes = $this->service->listResourceTypes();
        $this->isCollection($resourceTypes);
        $this->assertIsResourceType($resourceTypes->getElement(0));
    }

    public function testGetResourceType()
    {
        $this->addMockSubscriber($this->makeResponse('{"attributes":{"an_attribute":{"description":"An attribute description ."}},"properties":{"a_property":{"update_allowed":false,"required":true,"type":"string","description":"A resource description."}},"resource_type":"OS::Nova::Server"}'));

        $resourceType = $this->service->getResourceType('OS::Nova::Server');
        $this->assertIsResourceType($resourceType);
        $this->assertEquals('OS::Nova::Server', $resourceType->getResourceType());
    }

    public function testGetBuildInfo()
    {
        $this->addMockSubscriber($this->makeResponse('{"engine":{"revision":"2014.j3-20141003-1139"},"fusion-api":{"revision":"j1-20140915-10d9ee4-98"},"api":{"revision":"2014.j3-20141003-1139"}}'));

        $buildInfo = $this->service->getBuildInfo();
        $this->assertIsBuildInfo($buildInfo);
        $this->assertEquals('2014.j3-20141003-1139', $buildInfo->getApi()->revision);
    }

    public function testValidateInvalidTemplate()
    {
        $this->addMockSubscriber($this->makeResponse('{"explanation":"Template version not specified","code":400}', 400));
        try {
            $this->service->validateTemplate(array('template' => 'foobar: baz'));
            $this->fail("InvalidTemplateError exception should have been thrown!");
        } catch (InvalidTemplateError $e) {
            $this->assertEquals("Template version not specified", $e->getMessage());
        }
    }
}
