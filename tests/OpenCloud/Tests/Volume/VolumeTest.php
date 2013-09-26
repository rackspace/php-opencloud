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

use OpenCloud\Tests\StubConnection;
use OpenCloud\Volume\Service;
use OpenCloud\Volume\Resource\Volume;
use OpenCloud\Volume\Resource\VolumeType;

class publicVolume extends Volume
{
    public function createJson()
    {
        return parent::createJson();
    }
}

class VolumeTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $volume;

    public function __construct()
    {
        $service = $this->getClient()->volumeService('cloudBlockStorage', 'DFW');
        $this->volume = new publicVolume($service);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
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

    public function testCreateJson()
    {
        $service = new Service(
            new StubConnection('http://', 'S'), 
            'cloudBlockStorage', 
            'DFW', 
            'publicURL'
        );
        $type = new VolumeType($service);
        
        $type->name = 'SSD';
        $this->volume->volume_type = $type;
        $this->volume->display_name = 'BARFOO';
        $this->volume->metadata = array('one' => 'two');
        $obj = $this->volume->CreateJson();
        
        $this->assertEquals('BARFOO', $obj->volume->display_name);
    }

}
