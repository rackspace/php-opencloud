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

class UserTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $instance;
    private $user;

    public function __construct()
    {
        $this->service = $this->getClient()->dbService('cloudDatabases', 'DFW', 'publicURL');
        $this->instance = $this->service->instance('12345678');
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
            'https://dfw.databases.api.rackspacecloud.com/v1.0/9999/instances/56a0c515-9999-4ef1-9fe2-76be46a3aaaa/users/TEST', 
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
        $this->assertLessThan(205, $response->getStatusCode());
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
