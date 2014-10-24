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

class AlarmTest extends CloudMonitoringTestCase
{
    const ENTITY_ID = 'en5hw56rAh';
    const ALARM_ID = 'alAAAA';

    public function setupObjects()
    {
        parent::setupObjects();

        $this->resource = $this->entity->getAlarm();
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Alarm',
            $this->resource
        );

        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Entity',
            $this->resource->getParent()
        );
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/entities/' . self::ENTITY_ID . '/alarms',
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
     * @mockFile Entity_Test
     */
    public function testAlarmTesting()
    {
        $params = array();

        // Set criteria
        $params['criteria'] = 'if (metric["code"] == "404") { return new AlarmStatus(CRITICAL, "not found"); } return new AlarmStatus(OK);';

        // Data which needs to be tested
        $params['check_data'] = json_decode(file_get_contents(__DIR__ . '/test_existing.json'));

        $response = $this->resource->test($params);

        $this->assertObjectHasAttribute('timestamp', $response[0]);
        $this->assertObjectHasAttribute('status', $response[0]);

        $this->assertEquals('OK', $response[0]->state);
    }

    /**
     * @mockFile Alarm_List
     */
    public function testAlarmCollection()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->resource->listAll());
    }

    /**
     * @mockFile Alarm
     */
    public function testGetAlarm()
    {
        $this->resource->refresh(self::ALARM_ID);

        $this->assertEquals($this->resource->getId(), self::ALARM_ID);
        $this->assertEquals($this->resource->getParent()->getId(), self::ENTITY_ID);

        $this->expectOutputRegex('/return new AlarmStatus\(OK\)/');
        echo $this->resource->getCriteria();
    }

    public function testCreate()
    {
        $this->addMockSubscriber(new \Guzzle\Http\Message\Response(201));
        $this->resource->create(array(
            'check_id'             => 'foo',
            'notification_plan_id' => 'bar'
        ));
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AlarmException
     */
    public function testTestWithoutCriteriaParamFails()
    {
        $this->resource->test();
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AlarmException
     */
    public function testTestWithoutRequiredParamsFails()
    {
        $this->resource->test(array('criteria' => 'foobar'));
    }
}
