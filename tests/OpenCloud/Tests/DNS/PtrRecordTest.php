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

namespace OpenCloud\Tests\DNS;

use OpenCloud\Tests\StubConnection;
use PHPUnit_Framework_TestCase;
use OpenCloud\DNS\Service;
use OpenCloud\DNS\Resource\Object;
use OpenCloud\DNS\Resource\PtrRecord;

class CustomRecord extends Object
{
    public $name = 'fooBar';
}

class PtrRecordTest extends PHPUnit_Framework_TestCase
{

    private $conn;
    private $dns;
    private $record; // the record

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->dns = new Service(
            $this->conn, 'cloudDNS', array('N/A'), 'publicURL'
        );
        $this->record = new PtrRecord($this->dns);
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->record = new PtrRecord($this->dns);
        $this->assertEquals('PTR', $this->record->type);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\RecordTypeError
     */
    public function test__construct2()
    {
        // not allowed to change the record type from PTR
        $this->record = new PtrRecord(
            $this->dns, array('type' => 'A')
        );
    }

    public function testUrl()
    {
        $url = $this->record->url();
        $this->assertEquals($url, 'https://dns.api.rackspacecloud.com/v1.0/TENANT-ID/rdns');
    }

    public function testCreate()
    {
        $server = $this->conn->compute(null, array('ORD'))->server(array('id' => 'foo'));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->create(array('server' => $server))
        );
    }

    public function testUpdate()
    {
        $server = $this->conn->compute(null, array('ORD'))->server(array('id' => 'foo'));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->update(array('server' => $server))
        );
    }

    public function testDelete()
    {
        $server = $this->conn->compute(NULL, array('ORD'))->server(array('id' => 'foo'));
        $this->record->server = $server;
        $this->record->data   = 12345;
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->delete()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithoutKeys()
    {
        $object = new CustomRecord($this->dns);
        $object->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFailsWithoutKeys()
    {
        $object = new CustomRecord($this->dns);
        $object->update();
    }

}
