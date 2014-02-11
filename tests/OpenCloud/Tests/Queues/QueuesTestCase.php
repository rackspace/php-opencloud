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

namespace OpenCloud\Tests\Queues;

use OpenCloud\Tests\OpenCloudTestCase;

class QueuesTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $queue;

    protected $mockPath = 'Queues';

    public function setupObjects()
    {
        $this->service = $this->getClient()->queuesService('cloudQueues', 'ORD');

        $this->queue = $this->service->getQueue();
        $this->queue->setName('foo');
    }
}
