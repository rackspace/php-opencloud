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

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class CheckTest extends CloudMonitoringTestCase
{
    public function setupObjects()
    {
        parent::setupObjects();

        $this->resource = $this->entity->getCheck();
    }

    public function test_Create()
    {
        $this->addMockSubscriber(new Response(201));

        $this->entity->createCheck(array(
            'type' => 'webhook',
            'name' => 'TEST'
        ));
    }

    public function test_Check_Class()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\Check', $this->resource);
    }

    /**
     * @mockFile Check_List
     */
    public function test_List_All_Is_Collection()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->entity->getChecks());
    }

    public function test_Parent_Class()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\Entity', $this->resource->getParent());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Create_Fails_Without_Required_Params()
    {
        $this->resource->create();
    }

    public function test_Check_Test()
    {
        $response = new Response(200, array('Content-Type' => 'application/json'), '{"type":"remote.http","details":{"url":"http://www.foo.com","method":"GET"},"monitoring_zones_poll":["mzA"],"timeout":30,"target_alias":"default"}');
        $this->addMockSubscriber($response);

        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->testParams(array(), false);

        $this->assertNotNull($response);
        $this->assertObjectNotHasAttribute('debug_info', $response);
        $this->assertEquals('remote.http', $response->type);
    }

    public function test_Check_Test_With_Debug()
    {
        $response = new Response(200, array('Content-Type' => 'application/json'), '[{"timestamp":1319222001982,"monitoring_zone_id":"mzxJ4L2IU","available":true,"status":"code=200,rt=0.257s,bytes=0","metrics":{"bytes":{"type":"i","data":"0"},"tt_firstbyte":{"type":"I","data":"257"},"tt_connect":{"type":"I","data":"128"},"code":{"type":"s","data":"200"},"duration":{"type":"I","data":"257"}},"debug_info":{"body":"<html><body>My shiny website</body></html>"}}]');
        $this->addMockSubscriber($response);

        $this->resource->setType('remote.http');
        $this->resource->setLabel('Example label');
        $this->resource->setDisabled(false);

        $response = $this->resource->testParams(array(), true);

        $this->assertObjectHasAttribute('debug_info', $response[0]);
        $this->assertEquals('1319222001982', $response[0]->timestamp);
    }

    public function test_Existing_Check_Test()
    {
        $response = new Response(200, array('Content-Type' => 'application/json'), '[{"timestamp":1319222001982,"monitoring_zone_id":"mzxJ4L2IU","available":true,"status":"code=200,rt=0.257s,bytes=0","metrics":{"bytes":{"type":"i","data":"0"},"tt_firstbyte":{"type":"I","data":"257"},"tt_connect":{"type":"I","data":"128"},"code":{"type":"s","data":"200"},"duration":{"type":"I","data":"257"}}}]');
        $this->addMockSubscriber($response);

        $this->resource->setId('chAAAA');
        $this->resource->setType('remote.http');

        $response = $this->resource->test();

        $this->assertObjectHasAttribute('metrics', $response[0]);
        $this->assertObjectNotHasAttribute('debug_info', $response[0]);
        $this->assertEquals('mzxJ4L2IU', $response[0]->monitoring_zone_id);
    }

    /**
     * @mockFile Check
     */
    public function test_Get_Check()
    {
        $this->resource->refresh('chAAAA');

        $this->assertEquals($this->resource->getId(), 'chAAAA');
    }
}
