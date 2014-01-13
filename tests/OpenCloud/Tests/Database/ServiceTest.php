<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Database;

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

}
