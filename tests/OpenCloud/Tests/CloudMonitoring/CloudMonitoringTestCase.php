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

use OpenCloud\Tests\OpenCloudTestCase;

class CloudMonitoringTestCase extends OpenCloudTestCase
{
    const ENTITY_ID = 'en5hw56rAh';

    protected $service;
    protected $entity;

    protected $mockPath = 'CloudMonitoring';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $this->addMockSubscriber($this->getTestFilePath('Entity'));
        $this->entity = $this->service->getEntity(self::ENTITY_ID);
    }
}
