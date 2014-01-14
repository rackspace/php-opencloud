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

class DatabaseTest extends DatabaseTestCase
{
    private $database;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->makeResponse('{"name": "TEST"}'));
        $this->database = $this->instance->database();
    }

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Database', $this->database);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function test_Name()
    {
        $db = $this->database;
        $db->name = null;
        
        $db->getName();
    }

    public function test_Url()
    {
        $this->database->name = 'TEST';
        $this->assertEquals(
            'https://ord.databases.api.rackspacecloud.com/v1.0/1234/instances/dcc5c518-73c7-4471-83e1-15fae67a98eb/databases/TEST',
            (string) $this->database->getUrl()
        );
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Resource\Instance', 
            $this->database->instance()
        );
    }

    public function testCreate()
    {
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $this->database->create(array('name' => 'FOOBAR'))
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->database->update();
    }

    public function testDelete()
    {
        $this->database->name = 'FOOBAR';
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $this->database->delete()
        );
    }
    

    
}
