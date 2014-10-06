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

namespace OpenCloud\Tests\Orchestration;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\OpenCloudTestCase;

class OrchestrationTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $container;

    public function setupObjects()
    {
        $this->service = $this->getClient()->orchestrationService();

        $this->addMockSubscriber($this->makeResponse('{"engine":{"revision":"2014.j3-20141003-1139"},"fusion-api":{"revision":"j1-20140915-10d9ee4-98"},"api":{"revision":"2014.j3-20141003-1139"}}'));
        $this->buildInfo = $this->service->getBuildInfo();
    }
} 
