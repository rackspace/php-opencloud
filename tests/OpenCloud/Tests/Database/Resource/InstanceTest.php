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

namespace OpenCloud\Tests\Database\Resource;

use OpenCloud\Database\Resource\Instance;

class MyInstanceClass extends Instance
{
    public function createJson($parm = array())
    {
        return parent::CreateJson($parm);
    }
}

class InstanceTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;
    private $instance;

    public function __construct()
    {
        $this->service = $this->getClient()->databaseService('cloudDatabases', 'DFW', 'publicURL');
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
