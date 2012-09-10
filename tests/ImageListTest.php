<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('imagelist.inc');
require_once('compute.inc');
require_once('stub_conn.inc');
require_once('stub_service.inc');

class ImageListTest extends PHPUnit_Framework_TestCase
{
    private
        $imagelist,
        $service;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new OpenCloud\Compute(
            $conn,
            'cloudServersOpenStack',
            'DFW',
            'publicURL'
        );
        $this->imagelist = $this->service->ImageList();
    }
	/**
	 * Tests
	 */
	public function testNext() {
        $this->assertEquals('OpenCloud\Compute\ImageList', get_class($this->imagelist));
	}
}
