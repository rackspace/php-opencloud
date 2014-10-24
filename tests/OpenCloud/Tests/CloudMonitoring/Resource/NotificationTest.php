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

class NotificationTest extends CloudMonitoringTestCase
{
    const NOTIFICATION_ID = 'ntAAAA';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $response = new Response(200, array('Content-Type' => 'application/json'), '{"id":"ntAAAA","label":"my webhook #1","type":"webhook","details":{"url":"https://systems.example.org/alert"}}');
        $this->addMockSubscriber($response);
        $this->resource = $this->service->getNotification(self::NOTIFICATION_ID);
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Notification',
            $this->resource
        );
    }

    public function testResource()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/notifications/' . self::NOTIFICATION_ID,
            (string)$this->resource->getUrl()
        );

        $this->assertEquals('my webhook #1', $this->resource->getLabel());
    }

    public function test_Resource_Test()
    {
        $params = array(
            'label'   => 'Test notification',
            'type'    => 'webhook',
            'details' => array(
                'url' => 'http://test.com'
            )
        );

        $response = new Response(200, array('Content-Type' => 'application/json'), '{"type":"webhook","details":{"url":"http://my.web-server.com:5981/"}}');
        $this->addMockSubscriber($response);

        $response = $this->resource->testParams($params);
        $this->assertEquals('http://my.web-server.com:5981/', $response->details->url);
    }

    public function testExistingTest()
    {
        $response = new Response(200, array('Content-Type' => 'application/json'), '{"status":"success","message":"Success: Webhook successfully executed"}');
        $this->addMockSubscriber($response);

        $response = $this->resource->test();
        $this->assertEquals('success', $response->status);
    }
}
