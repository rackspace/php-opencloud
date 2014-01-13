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

namespace OpenCloud\Tests\Database\Resource;

use OpenCloud\Tests\Database\DatabaseTestCase;

class UserTest extends DatabaseTestCase
{
    private $user;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->user = $this->instance->user();
    }

    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\User', $this->user
        );
        
        $u = $this->instance->user('glen', array('one', 'two'));
        $this->assertEquals('glen', $u->name);
        $this->assertEquals(2, count($u->databases));
    }

    public function testUrl()
    {
        $this->user->name = 'TEST';
        $this->assertEquals(
            'https://ord.databases.api.rackspacecloud.com/v1.0/1234/instances/dcc5c518-73c7-4471-83e1-15fae67a98eb/users/TEST',
            (string) $this->user->getUrl()
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
        $this->assertLessThan(205, $response->getStatusCode());
        $this->assertEquals('FOOBAR', $this->user->getName());
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
        $this->assertLessThan(205, $response->getStatusCode());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function testNameFailsWhenNotSet()
    {
        $this->instance->user()->getName();
    }

}
