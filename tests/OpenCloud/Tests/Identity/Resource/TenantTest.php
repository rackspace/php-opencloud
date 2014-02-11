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


namespace OpenCloud\Tests\Identity\Resource;

use OpenCloud\Tests\Identity\IdentityTestCase;

class TenantTest extends IdentityTestCase
{
    public function test_Methods()
    {
        $this->addMockSubscriber($this->makeResponse('{"tenants":[{"id":1234}]}'));
        $tenants = $this->service->getTenants();
        $tenant = $tenants->current();

        $tenant->setId(321);
        $this->assertEquals(321, $tenant->getId());

        $tenant->setName('foo');
        $this->assertEquals('foo', $tenant->getName());

        $tenant->setDescription('bar');
        $this->assertEquals('bar', $tenant->getDescription());

        $tenant->setEnabled(false);
        $this->assertFalse($tenant->isEnabled());
    }
}
