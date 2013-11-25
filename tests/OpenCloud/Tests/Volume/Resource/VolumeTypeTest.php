<?php

namespace OpenCloud\Tests\Volume\Resource;

use OpenCloud\Tests\Volume\VolumeTestCase;

class VolumeTypeTest extends VolumeTestCase
{

    private $volumeType;

    public function setupObjects()
    {
        parent::setupObjects();
        $this->volumeType = $this->service->volumeType();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreate()
    {
        $this->volumeType->Create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->volumeType->Update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDelete()
    {
        $this->volumeType->Delete();
    }

    public function testJsonName()
    {
        $this->assertEquals('volume_type', $this->volumeType->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('types', $this->volumeType->ResourceName());
    }

}
