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

use OpenCloud\DNS\Resource\Object;

class CustomRecord extends Object
{
    public $name = 'fooBar';
}

class PtrRecordTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;
    private $record;

    public function __construct()
    {
        $this->service = $this->getClient()->dns('cloudDNS', 'N/A', 'publicURL');
        $this->record = $this->service->ptrRecord();
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->record = $this->service->ptrRecord();
        $this->assertEquals('PTR', $this->record->type);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\RecordTypeError
     */
    public function test__construct2()
    {
        $this->service->ptrRecord(array('type' => 'A'));
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dns.api.rackspacecloud.com/v1.0/TENANT-ID/rdns', 
            $this->record->url()
        );
    }

    public function testCreate()
    {
        $server = $this->getClient()->compute(null, 'ORD')->server(array('id' => 'foo'));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->create(array('server' => $server))
        );
    }

    public function testUpdate()
    {
        $server = $this->getClient()->compute(null, 'ORD')->server(array('id' => 'foo'));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->update(array('server' => $server))
        );
    }

    public function testDelete()
    {
        $server = $this->getClient()->compute(null, 'ORD')->server(array('id' => 'foo'));
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
        $object = new CustomRecord($this->service);
        $object->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFailsWithoutKeys()
    {
        $object = new CustomRecord($this->service);
        $object->update();
    }

}
