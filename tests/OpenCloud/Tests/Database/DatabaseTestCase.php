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

namespace OpenCloud\Tests\Database;

use OpenCloud\Tests\OpenCloudTestCase;
use OpenCloud\Tests\MockLogger;

class DatabaseTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $instance;

    protected $mockPath = 'Database';

    public function setupObjects()
    {
        $client = $this->getClient();
        $client->setLogger(new MockLogger());
        $this->service = $client->databaseService();

        $this->addMockSubscriber($this->getTestFilePath('Instance'));
        $this->instance = $this->service->instance('foo');

        $this->configuration = $this->service->configuration('005a8bb7-a8df-40ee-b0b7-fc144641abc2');
        $this->datastore = $this->service->datastore('10000000-0000-0000-0000-000000000001');
        $this->datastoreVersion = $this->datastore->version('b00000b0-00b0-0b00-00b0-000b000000bb');
        $this->backup = $this->service->backup();
    }

    protected function assertCriticalMessageWasLogged()
    {
        $this->assertNotEmpty($this->getClient()->getLogger()->getCriticalLogMessage());
    }
}
