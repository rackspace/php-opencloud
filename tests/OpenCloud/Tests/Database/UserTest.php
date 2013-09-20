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
use OpenCloud\Tests\StubConnection;
use OpenCloud\Database\Service;
use OpenCloud\Database\Resource\Instance;
use OpenCloud\Database\Resource\User;

class UserTest extends PHPUnit_Framework_TestCase
{

    private $inst;
    private $user;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $useraas = new Service(
            $conn, 'cloudDatabases', 'DFW', 'publicURL');
        
        $this->inst = new Instance($useraas);
        $this->inst->id = '12345678';
        
        $this->user = new User($this->inst);
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\User', new User($this->inst)
        );
        
        $u = new User($this->inst, 'glen', array('one', 'two'));
        $this->assertEquals('glen', $u->name);
        $this->assertEquals(2, count($u->databases));
    }

    public function testUrl()
    {
        $this->user->name = 'TEST';
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/' .
            'TENANT-ID/instances/12345678/users/TEST', 
            $this->user->url()
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\Instance', 
            $this->user->getParent()
        );
    }

    public function testService()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Service', 
            $this->user->getParent()->getService()
        );
    }

    public function testAddDatabase()
    {
        $this->user->addDatabase('FOO');
        $this->assertContains('FOO', $this->user->databases);
    }

    public function testCreate()
    {
        $response = $this->user->create(array(
            'name'     => 'FOOBAR',
            'password' => 'BAZ',
            'databases' => array(
                'foo',
                'baz'
            )
        ));
        $this->assertLessThan(205, $response->httpStatus());
        $this->assertEquals('FOOBAR', $this->user->name);
        $this->assertEquals('BAZ', $this->user->password);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->user->update();
    }

    public function testDelete()
    {
        $this->user->name = 'GLEN';
        $response = $this->user->delete();
        $this->assertLessThan(205, $response->HttpStatus());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function testNameFailsWhenNotSet()
    {
        $user = new User($this->inst);
        $user->getName();
    }

}
