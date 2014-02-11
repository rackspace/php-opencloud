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

namespace OpenCloud\Tests\Identity;

class ServiceTest extends IdentityTestCase
{
    public function test_Url()
    {
        $this->assertEquals('https://identity.api.rackspacecloud.com/v2.0/', (string)$this->service->getUrl());
    }

    public function test_Get_Users()
    {
        $this->assertNull($this->service->getUsers());

        $this->addMockSubscriber($this->makeResponse('{"users":[{"id":1234}]}'));
        $users = $this->service->getUsers();
        $this->assertInstanceOf('OpenCloud\Common\Collection\ResourceIterator', $users);
        $this->assertInstanceOf('OpenCloud\Identity\Resource\User', $users->current());
    }

    public function test_Get_User()
    {
        $this->assertInstanceOf('OpenCloud\Identity\Resource\User', $this->service->getUser('Foo Bar', 'name'));
        $this->assertInstanceOf('OpenCloud\Identity\Resource\User', $this->service->getUser('foo@bar.com', 'email'));
        $this->assertInstanceOf('OpenCloud\Identity\Resource\User', $this->service->getUser(1234, 'userId'));
        $this->service->getUser(1234, 'foo');
    }

    public function test_Create_User()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $user = $this->service->createUser(array('name' => 'foo'));
        $this->assertInstanceOf('OpenCloud\Identity\Resource\User', $user);
    }

    public function test_Get_Role()
    {
        $this->assertInstanceOf('OpenCloud\Identity\Resource\Role', $this->service->getRole('1234'));
    }

    public function test_Get_Roles()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection\PaginatedIterator', $this->service->getRoles());
    }

    public function test_Revoke_Token()
    {
        $response = $this->service->revokeToken(12345);
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    public function test_Get_Tenants()
    {
        $this->addMockSubscriber($this->makeResponse('{"tenants":[{"id":1234}]}'));
        $tenants = $this->service->getTenants();
        $this->assertInstanceOf('OpenCloud\Common\Collection\ResourceIterator', $tenants);
        $this->assertInstanceOf('OpenCloud\Identity\Resource\Tenant', $tenants->current());
    }
}
