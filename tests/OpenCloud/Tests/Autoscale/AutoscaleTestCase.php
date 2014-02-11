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

namespace OpenCloud\Tests\Autoscale;

use OpenCloud\Tests\OpenCloudTestCase;

class AutoscaleTestCase extends OpenCloudTestCase
{
    const GROUP_ID = '6742e741-cab6-42ff-abe5-458150afc9b1';
    const POLICY_ID = 'policyId';
    const WEBHOOK_ID = '23037efb-53a9-4ae5-bc33-e89a56b501b6';

    const CONFIG_CLASS = 'OpenCloud\Autoscale\Resource\GroupConfiguration';
    const GROUP_CLASS = 'OpenCloud\Autoscale\Resource\Group';

    protected $service;
    protected $group;
    protected $policy;

    protected $mockPath = 'Autoscale';

    public function setupObjects()
    {
        $this->service = $this->getClient()->autoscaleService('autoscale');

        $this->addMockSubscriber($this->getTestFilePath('Group'));
        $this->group = $this->service->group(self::GROUP_ID);
    }
}
