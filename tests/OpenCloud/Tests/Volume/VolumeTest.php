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

class VolumeTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $service;
    private $volume;

    public function __construct()
    {
        $this->service = $this->getClient()->volumeService('cloudBlockStorage', 'DFW');
        $this->volume = $this->service->volume();
    }
    
    public function test_Create()
    {
        $type = $this->service->volumeType('type_1');
        
        $volume = $this->service->volume()->create(array(
            'snapshot_id'           => 1,
            'display_name'          => 2,
            'display_description'   => 3,
            'size'                  => 4,
            'volume_type'           => $type,
            'availability_zone'     => 6,
            'metadata'              => array('foo' => 'bar')
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
