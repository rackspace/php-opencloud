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

require_once('volumetype.php');
require_once('stub_conn.php');
require_once('volumeservice.php');

class VolumeTypeTest extends PHPUnit_Framework_TestCase
{
    private
        $vt;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $serv = new OpenCloud\VolumeService(
            $conn, 'cloudBlockStorage', 'DFW', 'publicURL'
        );
        $this->vt = new OpenCloud\VolumeService\VolumeType($serv);
    }
    /**
     * Tests
     */
    /**
     * @expectedException OpenCloud\CreateError
     */
    public function testCreate() {
        $this->vt->Create();
    }
    /**
     * @expectedException OpenCloud\UpdateError
     */
    public function testUpdate() {
        $this->vt->Update();
    }
    /**
     * @expectedException OpenCloud\DeleteError
     */
    public function testDelete() {
        $this->vt->Delete();
    }
    public function testJsonName() {
        $this->assertEquals(
            'volume_type',
            $this->vt->JsonName());
    }
    public function testResourceName() {
        $this->assertEquals(
            'types',
            $this->vt->ResourceName());
    }
}
