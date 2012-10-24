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
    public function PrimaryKeyField() { return parent::PrimaryKeyField(); }
    public function CreateUrl() { return parent::CreateUrl(); }
    public function JsonName() { return parent::JsonName(); }
    public function ResourceName() { return parent::ResourceName(); }
    public function CreateJson() { return parent::CreateJson(); }
}

class VolumeAttachmentTest extends PHPUnit_Framework_TestCase
{
    private
        $att;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $compute = $conn->Compute(NULL, 'DFW');
        $this->att = new publicVolumeAttachment(
            new OpenCloud\Compute\Server($compute, 'XXX'),
            'FOO'
        );
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
            'Attachment []',
            $this->att->Name());
    }
    public function testPrimaryKeyField() {
        $this->assertEquals(
            'volumeId',
            $this->att->PrimaryKeyField());
    }
    public function testCreateUrl() {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/9bfd203a-0695-xxxx-yyyy-66c4194c967b/os-volume_attachments',
            $this->att->CreateUrl());
    }
    public function testJsonName() {
        $this->assertEquals(
            'volumeAttachment',
            $this->att->JsonName());
    }
    public function testResourceName() {
        $this->assertEquals(
            'os-volume_attachments',
            $this->att->ResourceName());
    }
    public function testCreateJson() {
        $obj = $this->att->CreateJson();
        $this->assertEquals(
            'FOO',
            $obj->volumeAttachment->volumeId);
    }
}
