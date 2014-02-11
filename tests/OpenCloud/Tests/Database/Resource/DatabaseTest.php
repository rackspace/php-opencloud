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

class DatabaseTest extends DatabaseTestCase
{
    private $database;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->makeResponse('{"name": "TEST"}'));
        $this->database = $this->instance->database();
    }

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Database', $this->database);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function test_Name()
    {
        $db = $this->database;
        $db->name = null;

        $db->getName();
    }

    public function test_Url()
    {
        $this->database->name = 'TEST';
        $this->assertEquals(
            'https://ord.databases.api.rackspacecloud.com/v1.0/1234/instances/dcc5c518-73c7-4471-83e1-15fae67a98eb/databases/TEST',
            (string)$this->database->getUrl()
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\Instance',
            $this->database->instance()
        );
    }

    public function testCreate()
    {
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $this->database->create(array('name' => 'FOOBAR'))
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->database->update();
    }

    public function testDelete()
    {
        $this->database->name = 'FOOBAR';
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $this->database->delete()
        );
    }
}
