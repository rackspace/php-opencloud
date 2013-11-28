<?php

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

    public function testSnapshot()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\Snapshot', $this->service->Snapshot());
    }

}
