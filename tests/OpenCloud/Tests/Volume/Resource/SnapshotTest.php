<?php

namespace OpenCloud\Tests\Volume\Resource;

use OpenCloud\Tests\Volume\VolumeTestCase;

class SnapshotTest extends VolumeTestCase
{

    private $snapshot;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->snapshot = $this->service->snapshot();
    }

    public function test_Create()
    {
        $this->snapshot->create(array());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->snapshot->update();
    }

    public function testName()
    {
        $this->snapshot->display_name = 'FOOBAR';
        $this->assertEquals('FOOBAR', $this->snapshot->Name());
    }

    public function testJsonName()
    {
        $this->assertEquals('snapshot', $this->snapshot->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('snapshots', $this->snapshot->ResourceName());
    }

}
