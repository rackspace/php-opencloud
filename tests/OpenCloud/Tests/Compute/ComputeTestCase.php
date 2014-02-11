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

namespace OpenCloud\Tests\Compute;

use OpenCloud\Tests\OpenCloudTestCase;

class ComputeTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $server;

    protected $mockPath = 'Compute';

    public function setupObjects()
    {
        $this->addMockSubscriber($this->getTestFilePath('Extensions'));
        $this->service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW', 'publicURL');

        $this->addMockSubscriber($this->getTestFilePath('Server'));
        $this->server = $this->service->server('SERVER-ID');
    }
}
