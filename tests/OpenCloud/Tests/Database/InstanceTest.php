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
use OpenCloud\Database\Instance;
use OpenCloud\Database\Service;
use OpenCloud\Tests\StubConnection;

class MyInstanceClass extends Instance
{

    public function createJson($parm = array())
    {
        return parent::CreateJson($parm);
    }

}

class InstanceTest extends PHPUnit_Framework_TestCase
{

    private $service;
    private $instance;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new Service(
            $conn, 'cloudDatabases', 'DFW', 'publicURL'
        );
        $this->instance = new MyInstanceClass(
            $this->service, 'INSTANCE-ID'
        );
    }

    /**
     * Tests
     */
    public function test___construct()
    {
        $this->assertInstanceOf('OpenCloud\Tests\MyInstanceClass', $this->instance);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->instance->update();
    }

    public function testRestart()
    {
        $this->assertEquals(200, $this->instance->restart()->httpStatus());
    }

    public function testResize()
    {
        $flavor = $this->service->Flavor(2);
        $this->assertEquals(200, $this->instance->Resize($flavor)->httpStatus());
    }

    public function testResizeVolume()
    {
        $this->assertEquals(200, $this->instance->ResizeVolume(4)->httpStatus());
    }

    public function testEnableRootUser()
    {
        $this->assertInstanceOf('OpenCloud\Database\User', $this->instance->enableRootUser());
    }

    public function testIsRootEnabled()
    {
        $this->assertFalse($this->instance->IsRootEnabled());
    }

    public function testDatabase()
    {
        $this->assertInstanceOf('OpenCloud\Database\Database', $this->instance->database('FOO'));
    }

    public function testUser()
    {
        // user with 2 databases
        $u = $this->instance->User('BAR', array('FOO', 'BAR'));
        $this->assertInstanceOf('OpenCloud\Database\User', $u);
        // make sure it has 2 databases
        $this->assertEquals(2, count($u->databases));
    }

    public function testDatabaseList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->instance->databaseList());
    }

    public function testUserList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->instance->userList());
    }

    public function testCreateJson()
    {
        $this->instance->name = 'FOOBAR';
        $obj = $this->instance->createJson();
        $this->assertEquals('FOOBAR', $obj->instance->name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InstanceFlavorError
     */
    public function testCreateFailsWithoutFlavor()
    {
        $instance = new MyInstanceClass($this->service);
        $instance->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InstanceError
     */
    public function testCreateFailsWithoutName()
    {
        $instance = new MyInstanceClass($this->service);
        $instance->flavor = (object) array('links' => array('href' => 'bar'));
        $instance->create();
    }

}
