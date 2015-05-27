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

namespace OpenCloud\Tests\Database;

use OpenCloud\Database\Service;

class ServiceTest extends DatabaseTestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Service',
            $this->getClient()->databaseService('cloudDatabases', 'DFW')
        );
    }

    public function testFlavorList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->flavorList());
    }

    public function testDbInstance()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Instance', $this->service->Instance());
    }

    public function testDbInstanceList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->InstanceList());
    }

    public function testConfiguration()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Configuration', $this->service->Configuration());
    }

    public function testConfigurationList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->configurationList());
    }

    public function testDatastore()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Datastore', $this->service->Datastore());
    }

    public function testDatastoreList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->datastoreList());
    }

    public function testBackup()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Backup', $this->service->Backup());
    }

    public function testBackupList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->backupList());
    }
}
