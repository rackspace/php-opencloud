<?php

namespace OpenCloud\Tests\Volume\Resource;

use OpenCloud\Tests\Volume\VolumeTestCase;

class VolumeTest extends VolumeTestCase
{
    public function test_Create()
    {
        $this->addMockSubscriber($this->makeResponse('{"volume_type":{"id":"1","name":"SATA","extra_specs":{}}}'));
        $type = $this->service->volumeType('type_1');
        
        $volume = $this->service->volume()->create(array(
            'snapshot_id'         => 1,
            'display_name'        => 2,
            'display_description' => 3,
            'size'                => 4,
            'volume_type'         => $type,
            'availability_zone'   => 6,
            'metadata'            => array('foo' => 'bar')
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Update()
    {
        $this->volume->Update();
    }

    public function testName()
    {
        $this->volume->display_name = 'FOOBAR';
        $this->assertEquals('FOOBAR', $this->volume->Name());
    }

    public function testJsonName()
    {
        $this->assertEquals('volume', $this->volume->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('volumes', $this->volume->ResourceName());
    }

}
