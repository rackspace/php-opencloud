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

class UserTest extends IdentityTestCase
{
    public function setupObjects()
    {
        parent::setupObjects();
        $this->user = $this->service->resource('User');
    }

    public function test_Methods()
    {
        $body = '{"user": {"RAX-AUTH:defaultRegion": "DFW","RAX-AUTH:domainId": "5830280","id": "123456","username": "jqsmith","email": "john.smith@example.org","enabled": true}}';
        $this->addMockSubscriber($this->makeResponse($body));
        $user = $this->service->getUser('foo');

        $this->assertEquals(5830280, $user->getDomainId());
        $user->setDomainId(123);
        $this->assertEquals(123, $user->getDomainId());

        $this->assertEquals(123456, $user->getId());

        $user->setEmail('foo@bar.com');
        $this->assertEquals('foo@bar.com', $user->getEmail());

        $user->setEnabled(true);
        $this->assertTrue($user->isEnabled());
    }

    public function test_Update()
    {
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->user->update(array('username' => 'foo')));
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->user->updatePassword('new'));
    }

    public function test_Get_Other_Credentials()
    {
        $this->assertNull($this->user->getOtherCredentials());

        $body = '{"credentials":[{"RAX-KSKEY:apiKeyCredentials":{"apiKey":"0f97f489c848438090250d50c7e1eaXZ","username":"bobbuilder"}}]}';
        $this->addMockSubscriber($this->makeResponse($body));

        $creds = $this->user->getOtherCredentials();
        $this->assertCount(1, $creds);
        $this->assertEquals('0f97f489c848438090250d50c7e1eaXZ', $creds[0]->{"RAX-KSKEY:apiKeyCredentials"}->apiKey);
    }

    /**
     * @covers OpenCloud\Identity\Resource\User::resetApiKey()
     */
    public function test_Get_Api_Key()
    {
        $this->assertNull($this->user->getApiKey());

        $body = '{"RAX-KSKEY:apiKeyCredentials":{"username":"demoauthor","apiKey":"aaaaa-bbbbb-ccccc-12345678"}}';
        $this->addMockSubscriber($this->makeResponse($body));

        $this->assertEquals('aaaaa-bbbbb-ccccc-12345678', $this->user->getApiKey());
    }

    /**
     * @covers OpenCloud\Identity\Resource\User::removeRole()
     */
    public function test_Get_Role()
    {
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->user->addRole(123));
    }

    public function test_Get_Roles()
    {
        $body = '{"roles":[{"id":"123","name":"compute:admin","description":"Nova Administrator"}],"roles_links":[]}';
        $this->addMockSubscriber($this->makeResponse($body));
        $this->assertInstanceOf('OpenCloud\Common\Collection\ResourceIterator', $this->user->getRoles());
    }
}
