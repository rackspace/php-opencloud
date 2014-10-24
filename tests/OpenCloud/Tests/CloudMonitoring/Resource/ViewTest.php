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

class ViewTest extends CloudMonitoringTestCase
{
    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $this->addMockSubscriber($this->getTestFilePath('View'));
        $this->resource = $this->service->getViews();
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->resource);

        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\View', $this->resource->first());
    }

    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/views/overview',
            (string)$this->resource->first()->getUrl()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailWithNoParams()
    {
        $this->resource->first()->create();
    }

    public function test_Values()
    {
        $item = $this->resource->first();

        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Entity', $item->getEntity());

        $this->assertInstanceOf(self::COLLECTION_CLASS, $item->getAlarms());
        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Alarm', $item->getAlarms()->first());

        $this->assertInstanceOf(self::COLLECTION_CLASS, $item->getChecks());
        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Check', $item->getChecks()->first());
    }
}
