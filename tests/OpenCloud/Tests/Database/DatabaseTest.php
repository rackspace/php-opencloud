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

use PHPUnit_Framework_TestCase;
use OpenCloud\Tests\StubConnection;
use OpenCloud\Database\Database;
use OpenCloud\Database\Instance;
use OpenCloud\Database\Service;

class DatabaseTest extends PHPUnit_Framework_TestCase
{

    private $inst;
    private $db;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $dbaas = new Service($conn, 'cloudDatabases', array('DFW'), 'publicURL');
        $this->inst = new Instance($dbaas);
        $this->inst->id = '12345678';
        $this->db = new Database($this->inst);
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Database\Database', $this->db);
    }

    public function testUrl()
    {
        $this->db->name = 'TEST';
        $urls = $this->db->url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.databases.api.rackspacecloud.com/v1.0/' .
                        'TENANT-ID/instances/12345678/databases/TEST');
        }
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'OpenCloud\Database\Instance', 
            $this->db->instance()
        );
    }

    public function testCreate()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Request\Response\Blank', 
            $this->db->create(array('name' => 'FOOBAR'))
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->db->update();
    }

    public function testDelete()
    {
        $this->db->name = 'FOOBAR';
        $this->assertInstanceOf(
            'OpenCloud\Common\Request\Response\Blank', 
            $this->db->delete()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DatabaseNameError
     */
    public function testUrlFailsWithoutName()
    {
        $db = new Database($this->inst);
        $db->url();
    }
    
}
