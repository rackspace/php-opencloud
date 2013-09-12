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

use PHPUnit_Framework_TestCase;
use OpenCloud\Database\Service;
use OpenCloud\Tests\StubConnection;

class ServiceTest extends PHPUnit_Framework_TestCase
{
    
    private $connection;
    private $dbaas;

    public function __construct()
    {
        $this->connection = new StubConnection('http://example.com', 'secret');
        $this->dbaas = new Service(
            $this->connection, 'cloudDatabases', 'DFW', 'publicURL'
        );
    }

    public function test__construct()
    {
        $this->dbaas = new Service(
            $this->connection, 'cloudDatabases', 'DFW', 'publicURL'
        );
        $this->assertInstanceOf('OpenCloud\Database\Service', $this->dbaas);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/TENANT-ID/instances', 
            $this->dbaas->Url()
        );
        
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/TENANT-ID/instances/INSTANCE-ID', 
            $this->dbaas->Url('instances/INSTANCE-ID')
        );
    }

    public function testFlavorList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->dbaas->flavorList());
    }

    public function testDbInstance()
    {
        $inst = $this->dbaas->Instance();
        $this->assertInstanceOf('OpenCloud\Database\Instance', $inst);
    }

    public function testDbInstanceList()
    {
        $list = $this->dbaas->InstanceList();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $list);
    }

}
