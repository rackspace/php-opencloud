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

namespace OpenCloud\Tests\Database;

class DatabaseTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $instance;
    private $database;

    public function __construct()
    {
        $service = $this->getClient()->dbService('cloudDatabases', 'DFW', 'publicURL');
        $this->instance = $service->instance('12345678');
        $this->database = $this->instance->database();
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Database', $this->database);
    }

    public function testUrl()
    {
        $this->database->name = 'TEST';
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/' .
            'TENANT-ID/instances/12345678/databases/TEST', $this->database->url()
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
            'OpenCloud\Common\Request\Response\Blank', 
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
            'OpenCloud\Common\Request\Response\Blank', 
            $this->database->delete()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function testUrlFailsWithoutName()
    {
        $db = $this->instance->database();
        $db->url();
    }
    
}
