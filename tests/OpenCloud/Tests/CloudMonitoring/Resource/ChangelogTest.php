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

namespace OpenCloud\Tests\CloudMonitoring;

class ChangelogTest extends CloudMonitoringTestCase
{
    const NT_ID = 'webhook';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();
        $this->addMockSubscriber($this->getTestFilePath('Changelogs'));
        $this->resource = $this->service->getChangelog()->first();
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Changelog',
            $this->resource
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
     * @mockFile Changelogs
     */
    public function testListAll()
    {
        $list = $this->service->getChangelog();

        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);

        $first = $list->first();

        $this->assertEquals('4c5e28f0-0b3f-11e1-860d-c55c4705a286', $first->getId());
        $this->assertEquals('enPhid7noo', $first->getEntityId());
    }
}
