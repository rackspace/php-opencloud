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

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version   1.0.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Database\Resource;

use OpenCloud\Tests\Database\DatabaseTestCase;

class UserTest extends DatabaseTestCase
{
    private $user;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->user = $this->instance->user();
    }

    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\User', $this->user
        );

        $u = $this->instance->user('glen', ['one', 'two']);
        $this->assertEquals('glen', $u->name);
        $this->assertEquals(2, count($u->databases));
    }

    public function testUrl()
    {
        $this->user->name = 'TEST';
        $this->assertEquals(
            'https://ord.databases.api.rackspacecloud.com/v1.0/1234/instances/dcc5c518-73c7-4471-83e1-15fae67a98eb/users/TEST',
            (string)$this->user->getUrl()
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\Instance',
            $this->user->getParent()
        );
    }

    public function testService()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Service',
            $this->user->getParent()->getService()
        );
    }

    public function testAddDatabase()
    {
        $this->user->addDatabase('FOO');
        $this->assertContains('FOO', $this->user->databases);
    }

    public function testCreate()
    {
        $response = $this->user->create([
            'name'      => 'FOOBAR',
            'password'  => 'BAZ',
            'databases' => [
                'foo',
                'baz'
            ]
        ]);
        $this->assertLessThan(205, $response->getStatusCode());
        $this->assertEquals('FOOBAR', $this->user->getName());
        $this->assertEquals('BAZ', $this->user->password);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->user->update();
    }

    public function testDelete()
    {
        $this->user->name = 'GLEN';
        $response = $this->user->delete();
        $this->assertLessThan(205, $response->getStatusCode());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function testNameFailsWhenNotSet()
    {
        $this->instance->user()->getName();
    }

    public function test_It_Grants_Access_To_Db()
    {
        $this->user->name = 'foo';

        $response = $this->user->grantDbAccess([
            'foo', 'bar', 'baz',
        ]);

        $this->isResponse($response);
    }
}
