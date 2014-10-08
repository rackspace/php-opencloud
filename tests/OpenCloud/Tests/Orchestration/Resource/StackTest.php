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

use OpenCloud\Orchestration\Resource\Stack;
use OpenCloud\Tests\Orchestration\OrchestrationTestCase;

class PublicStack extends Stack
{
    public function createJson($params = array())
    {
        return parent::createJson($params);
    }

    public function updateJson($params = array())
    {
        return parent::updateJson($params);
    }
}

class StackTest extends OrchestrationTestCase
{
    public function testCreateJson()
    {

        $createParams = array(
            'name' => 'foobar',
            'templateUrl' => 'https://github.com/ycombinator/drupal-multi/template.yml',
            'parameters' => array(
                'flavor_id' => 'performance1_1',
                'db_name'   => 'drupaldb',
                'db_user'   => 'drupaldbuser'
            ),
            'timeoutMins' => 5
        );

        $stack = new PublicStack($this->service);
        $stack->create($createParams);
        $createJson = $stack->createJson();

        $expectedObj = (object) array(
            'stack_name' => 'foobar',
            'template_url' => 'https://github.com/ycombinator/drupal-multi/template.yml',
            'parameters' => array(
                'flavor_id' => 'performance1_1',
                'db_name'   => 'drupaldb',
                'db_user'   => 'drupaldbuser'
            ),
            'timeout_mins' => 5
        );

        $this->assertEquals($expectedObj, $createJson);
    }

    public function testUpdateJson()
    {

        $updateParams = array(
            'templateUrl' => 'https://github.com/ycombinator/drupal-multi/template.yml',
            'parameters' => array(
                'flavor_id' => 'performance1_1',
                'db_name'   => 'drupaldb',
                'db_user'   => 'drupalwebuser'
            ),
            'timeoutMins' => 10
        );

        $stack = new PublicStack($this->service);
        $stack->update($updateParams);
        $updateJson = $stack->updateJson();

        $expectedObj = (object) array(
            'template_url' => 'https://github.com/ycombinator/drupal-multi/template.yml',
            'parameters' => array(
                'flavor_id' => 'performance1_1',
                'db_name'   => 'drupaldb',
                'db_user'   => 'drupalwebuser'
            ),
            'timeout_mins' => 10
        );

        $this->assertEquals($expectedObj, $updateJson);
    }

    public function testAbandonStack()
    {

        $expectedAbandonStackData = '{"status":"COMPLETE","name":"g","dry_run":true,"template":{"outputs":{"instance_ip":{"value":{"str_replace":{"params":{"username":"ec2-user","hostname":{"get_attr":["server","first_address"]}},"template":"ssh username@hostname"}}}},"heat_template_version":"2013-05-23","resources":{"server":{"type":"OS::Nova::Server","properties":{"key_name":{"get_param":"key_name"},"image":{"get_param":"image"},"flavor":{"get_param":"flavor"}}}},"parameters":{"key_name":{"default":"heat_key","type":"string"},"image":{"default":"Ubuntu 12.04 LTS (Precise Pangolin)","type":"string"},"flavor":{"default":"1 GB Performance","type":"string"}}},"action":"CREATE","id":"16934ca3-40e0-4fb2-a289-a700662ec05a","resources":{"server":{"status":"COMPLETE","name":"server","resource_data":{},"resource_id":"39d5dad7-7d7a-4cc8-bd84-851e9e2ff4ea","action":"CREATE","type":"OS::Nova::Server","metadata":{}}}}';
        $this->addMockSubscriber($this->makeResponse($expectedAbandonStackData));

        $actualAbandonStackData = $this->stack->abandon();
        $this->assertEquals($expectedAbandonStackData, $actualAbandonStackData);
    }

    public function testListResources()
    {
        $this->addMockSubscriber($this->makeResponse('{"resources":[{"resource_name":"MySqlCloudDatabaseServer","links":[{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxxx1-9xx9-4xxe-bxxf-a7xxxxxd99068/resources/MySqlCloudDatabaseServer","rel":"self"},{"href":"http:s//dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxxx1-9xx9-4xxe-bxxf-a7xxxxx068","rel":"stack"}],"logical_resource_id":"MySqlCloudDatabaseServer","resource_status_reason":"state changed","updated_time":"2014-02-05T19:20:31Z","required_by":[],"resource_status":"CREATE_COMPLETE","physical_resource_id":"984xxxxxe0-c7x8-4x6e-be15-3f0xxxxx711","resource_type":"OS::Trove::Instance"}]}'));

        $resources = $this->stack->listResources();
        $this->assertInstanceOf(self::COLLECTION_CLASS, $resources);

        $firstResource = $resources->getElement(0);
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Resource', $firstResource);
        $this->assertEquals('MySqlCloudDatabaseServer', $firstResource->getName());
    }

    public function testGetResource()
    {
        $this->addMockSubscriber($this->makeResponse('{"resource":{"resource_name":"MySqlCloudDatabaseServer","description":"A MySQL DB instance","links":[{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxxx21-9xx9-4xxxe-bxxf-a7fxxxxx68/resources/MySqlCloudDatabaseServer","rel":"self"},{"href":"https://dfw.orchestration.rackspacecloud.com/v1/tenant_id/stacks/trove2/87xxxx1-9xx9-4xxe-bxxf-a7fxxxxxx68","rel":"stack"}],"logical_resource_id":"MySqlCloudDatabaseServer","resource_status":"CREATE_COMPLETE","updated_time":"2014-02-05T19:20:31Z","required_by":[],"resource_status_reason":"state changed","physical_resource_id":"98xxx0-cxx8-4xxe-bxx5-3fxxxx11","resource_type":"OS::Trove::Instance"}}'));

        $resource = $this->stack->getResource('MySqlCloudDatabaseServer');
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Resource', $resource);
        $this->assertEquals('A MySQL DB instance', $resource->getDescription());
    }
}
