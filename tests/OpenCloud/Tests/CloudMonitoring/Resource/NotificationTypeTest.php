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

class NotificationTypeTest extends CloudMonitoringTestCase
{
    const NT_ID = 'webhook';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $response = new Response(200, array('Content-Type' => 'application/json'), '{"id":"webhook","fields":[{"name":"url","description":"An HTTP or HTTPS URL to POST to","optional":false}]}');
        $this->addMockSubscriber($response);

        $this->resource = $this->service->getNotificationType(self::NT_ID);
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\NotificationType',
            $this->resource
        );
    }

    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/notification_types/webhook',
            (string)$this->resource->getUrl()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailWithNoParams()
    {
        $this->resource->create();
    }

    /**
     * @mockFile NotificationType_List
     */
    public function testListAll()
    {
        $list = $this->service->getNotificationTypes();

        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $list
        );

        $first = $list->first();

        $this->assertEquals('webhook', $first->getId());
        $fields = $first->getFields();
        $this->assertEquals('An HTTP or HTTPS URL to POST to', $fields[0]->description);
    }

    public function testGet()
    {
        $fields = $this->resource->getFields();
        $this->assertEquals('url', $fields[0]->name);
        $this->assertFalse($fields[0]->optional);
    }
}
