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

}
