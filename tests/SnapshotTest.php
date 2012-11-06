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

require_once('snapshot.inc');
require_once('stub_conn.inc');
require_once('volumeservice.inc');

class publicSnapshot extends OpenCloud\VolumeService\Snapshot {
    public function CreateJson() { return parent::CreateJson(); }
}

class SnapshotTest extends PHPUnit_Framework_TestCase
{
    private
        $snap;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $serv = new OpenCloud\VolumeService(
            $conn, 'cloudBlockStorage', 'DFW', 'publicURL'
        );
        $this->snap = new publicSnapshot($serv);
    }
    /**
     * Tests
     */
    /**
     * @expectedException OpenCloud\UpdateError
     */
    public function testUpdate() {
        $this->snap->Update();
    }
    public function testName() {
        $this->snap->display_name = 'FOOBAR';
        $this->assertEquals(
            'FOOBAR',
            $this->snap->Name());
    }
    public function testJsonName() {
        $this->assertEquals(
            'snapshot',
            $this->snap->JsonName());
    }
    public function testResourceName() {
        $this->assertEquals(
            'snapshots',
            $this->snap->ResourceName());
    }
    public function testCreateJson() {
        $this->snap->display_name = 'BARFOO';
        $obj = $this->snap->CreateJson();
        $this->assertEquals(
            'BARFOO',
            $obj->snapshot->display_name);
    }
}
