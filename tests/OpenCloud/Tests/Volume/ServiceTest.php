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

namespace OpenCloud\Tests\Volume;

use PHPUnit_Framework_TestCase;
use OpenCloud\Volume\Service;
use OpenCloud\Tests\StubConnection;

class ServiceTest extends PHPUnit_Framework_TestCase
{

    private $conn;
    private $service;

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new Service(
            $this->conn, 'cloudBlockStorage', 'DFW', 'publicURL'
        );
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->service = new Service(
            $this->conn, 'cloudBlockStorage', 'DFW', 'publicURL'
        );
        $this->assertInstanceOf('OpenCloud\Volume\Service', $this->service);
    }

    public function testVolume()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Volume', $this->service->Volume());
    }

    public function testVolumeList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->service->VolumeList());
    }

    public function testVolumeType()
    {
        $this->assertInstanceOf('OpenCloud\Volume\VolumeType', $this->service->VolumeType());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CollectionError
     */
    public function testVolumeTypeList()
    {
        $this->service->VolumeTypeList();
    }

    public function testSnapshot()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Snapshot', $this->service->Snapshot());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CollectionError
     */
    public function testSnapshotList()
    {
        $this->service->SnapshotList();
    }

    public function testRequest()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Request\Response\Blank', 
            $this->service->Request('http://me.com')
        );
    }

}
