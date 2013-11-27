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

class ServiceTest extends VolumeTestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Volume\Service', 
            $this->service
        );
    }

    public function testVolume()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\Volume', $this->service->Volume());
    }

    public function testVolumeList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->volumeList());
    }

    public function testVolumeType()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\VolumeType', $this->service->VolumeType());
    }

    /**
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testVolumeTypeList()
    {
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->service->VolumeTypeList();
    }

    public function testSnapshot()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\Snapshot', $this->service->Snapshot());
    }

    /**
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testSnapshotList()
    {
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->service->SnapshotList();
    }

}
