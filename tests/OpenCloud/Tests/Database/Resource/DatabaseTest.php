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

class DatabaseTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $instance;
    private $database;

    public function __construct()
    {
        $service = $this->getClient()->databaseService('cloudDatabases', 'DFW', 'publicURL');
        $this->instance = $service->instance('56a0c515-9999-4ef1-9fe2-76be46a3aaaa');
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

    public function testUrl()
    {
        $this->database->name = 'TEST';
        $this->assertEquals(
            'https://dfw.databases.api.rackspacecloud.com/v1.0/9999/instances/56a0c515-9999-4ef1-9fe2-76be46a3aaaa/databases/TEST', 
            (string) $this->database->url()
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
            'OpenCloud\Common\Http\Message\Response', 
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
            'OpenCloud\Common\Http\Message\Response', 
            $this->database->delete()
        );
    }
    

    
}
