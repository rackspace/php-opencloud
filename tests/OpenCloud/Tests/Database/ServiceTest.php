<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests;

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    
    private $service;

    public function __construct()
    {
        $this->service = $this->getClient()->dbService('cloudDatabases', 'DFW', 'publicURL');
    }

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Database\Service', $this->service);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/TENANT-ID/instances', 
            $this->service->Url()
        );
        
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/TENANT-ID/instances/INSTANCE-ID', 
            $this->service->Url('instances/INSTANCE-ID')
        );
    }

    public function testFlavorList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->service->flavorList());
    }

    public function testDbInstance()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Instance', $this->service->Instance());
    }

    public function testDbInstanceList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->service->InstanceList());
    }

}
