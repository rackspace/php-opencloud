<?php
/**
 * Unit Tests
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

require_once('volume.inc');
require_once('stub_conn.inc');
require_once('volumeservice.inc');

class publicVolume extends OpenCloud\VolumeService\Volume {
    public function JsonName() { return parent::JsonName(); }
    public function ResourceName() { return parent::ResourceName(); }
    public function CreateJson() { return parent::CreateJson(); }
}

class VolumeTest extends PHPUnit_Framework_TestCase
{
    private
        $vol;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $serv = new OpenCloud\VolumeService(
            $conn, 'cloudBlockStorage', 'DFW', 'publicURL'
        );
        $this->vol = new publicVolume($serv);
    }
    /**
     * Tests
     */
    /**
     * @expectedException OpenCloud\UpdateError
     */
    public function testUpdate() {
        $this->vol->Update();
    }
    public function testName() {
        $this->vol->display_name = 'FOOBAR';
        $this->assertEquals(
            'FOOBAR',
            $this->vol->Name());
    }
    public function testJsonName() {
        $this->assertEquals(
            'volume',
            $this->vol->JsonName());
    }
    public function testResourceName() {
        $this->assertEquals(
            'volumes',
            $this->vol->ResourceName());
    }
    public function testCreateJson() {
        $this->vol->display_name = 'BARFOO';
        $this->vol->metadata = array('one' => 'two');
        $obj = $this->vol->CreateJson();
        $this->assertEquals(
            'BARFOO',
            $obj->volume->display_name);
    }
}
