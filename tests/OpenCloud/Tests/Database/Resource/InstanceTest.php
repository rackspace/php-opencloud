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

namespace OpenCloud\Tests\Database\Resource;

use OpenCloud\Tests\Database\DatabaseTestCase;

class InstanceTest extends DatabaseTestCase
{
    public function test_Class()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Instance', $this->instance);
    }

    public function testUpdateJson()
    {
        $replacementValues = array(
            'configuration' => '005a8bb7-a8df-40ee-b0b7-fc144641abc2'
        );

        $method = new \ReflectionMethod('OpenCloud\Database\Resource\Instance', 'updateJson');
        $method->setAccessible(true);

        $expected = (object) array(
            'instance' => $replacementValues
        );
        $this->assertEquals($expected, $method->invoke($this->instance, $replacementValues));
    }

    public function testRestart()
    {
        $this->assertNotNull($this->instance->restart()->getBody());
    }

    public function testResize()
    {
        $flavor = $this->service->Flavor(2);
        $this->assertNotNull($this->instance->Resize($flavor)->getBody());
    }

    public function testResizeVolume()
    {
        $this->assertNotNull($this->instance->resizeVolume(4)->getBody());
    }

    public function testEnableRootUser()
    {
        $this->addMockSubscriber($this->makeResponse('{"user":{"name":"root","password":"12345"}}'));
        $this->assertInstanceOf('OpenCloud\Database\Resource\User', $this->instance->enableRootUser());
    }

    public function testIsRootEnabled()
    {
        $this->assertFalse($this->instance->IsRootEnabled());
    }

    public function testDatabase()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Database', $this->instance->database('FOO'));
    }

    public function testUser()
    {
        // user with 2 databases
        $u = $this->instance->User('BAR', array('FOO', 'BAR'));
        $this->assertInstanceOf('OpenCloud\Database\Resource\User', $u);
        // make sure it has 2 databases
        $this->assertEquals(2, count($u->databases));
    }

    public function testDatabaseList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->instance->databaseList());
    }

    public function testUserList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->instance->userList());
    }

    public function testBackupList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->instance->backupList());
    }

    public function testCreateBackup()
    {
        $backup = $this->instance->createBackup(array(
            'name' => 'test-backup',
            'description' => 'test'
        ));
        $this->assertInstanceOf('OpenCloud\Database\Resource\Backup', $backup);
    }
}
