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

class VolumeTest extends PHPUnit_Framework_TestCase
{

    private $vol;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $serv = new Service(
            $conn, 'cloudBlockStorage', 'DFW', 'publicURL'
        );
        $this->vol = new publicVolume($serv);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->vol->Update();
    }

    public function testName()
    {
        $this->vol->display_name = 'FOOBAR';
        $this->assertEquals('FOOBAR', $this->vol->Name());
    }

    public function testJsonName()
    {
        $this->assertEquals('volume', $this->vol->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('volumes', $this->vol->ResourceName());
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
        $this->vol->volume_type = $type;
        $this->vol->display_name = 'BARFOO';
        $this->vol->metadata = array('one' => 'two');
        $obj = $this->vol->CreateJson();
        
        $this->assertEquals('BARFOO', $obj->volume->display_name);
    }

}
