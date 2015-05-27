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

class BackupTest extends DatabaseTestCase
{
    public function test_Class()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Backup', $this->backup);
    }

    public function testDelete()
    {
        $this->backup->delete();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\BackupInstanceError
     */
    public function test_Create_Fails_Without_InstanceId()
    {
        $this->assertFalse($this->backup->create(array(
            'name' => 'test',
            'description' => 'test desc'
        )));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\BackupNameError
     */
    public function test_Create_Fails_Without_Name()
    {
        $this->assertFalse($this->backup->create(array(
            'description' => 'test description',
            'instanceId' => '1234'
        )));
    }

    public function testCreate()
    {
        $this->backup->create(array(
            'name' => 'test backup',
            'instanceId' => '1234'
        ));

        $this->assertEquals('test backup', $this->backup->name);
        $this->assertEquals('1234', $this->backup->instanceId);
    }
}
