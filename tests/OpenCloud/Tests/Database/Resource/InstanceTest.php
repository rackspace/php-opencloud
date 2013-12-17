<?php

namespace OpenCloud\Tests\Database\Resource;

use OpenCloud\Tests\Database\DatabaseTestCase;

class InstanceTest extends DatabaseTestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Instance', $this->instance);
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

}