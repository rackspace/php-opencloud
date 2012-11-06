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

require_once('stub_conn.inc');
require_once('stub_service.inc');
require_once('volumeattachment.inc');

class publicVolumeAttachment extends OpenCloud\Compute\VolumeAttachment {
    public function CreateJson() { return parent::CreateJson(); }
}

class VolumeAttachmentTest extends PHPUnit_Framework_TestCase
{
    private
        $att;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $compute = $conn->Compute(NULL, 'DFW');
        //setDebug(TRUE);
        $this->att = new publicVolumeAttachment(
            new OpenCloud\Compute\Server($compute, 'XXX'),
            'FOO'
        );
        setDebug(FALSE);
    }
    /**
     * Tests
     */
    public function test__construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $compute = $conn->Compute(NULL, 'DFW');
        $this->att = new publicVolumeAttachment(
            new OpenCloud\Compute\Server($compute, 'XXX'),
            'FOO'
        );
        $this->assertEquals(
            'FOO',
            $this->att->volumeId);
    }
    /**
     * @expectedException OpenCloud\UpdateError
     */
    public function testUpdate() {
        $this->att->Update();
    }
    public function testName() {
        $this->assertEquals(
            'Attachment [FOO]',
            $this->att->Name());
    }
    public function testCreateJson() {
        $obj = $this->att->CreateJson();
        $this->assertEquals(
            'FOO',
            $obj->volumeAttachment->volumeId);
    }
}
