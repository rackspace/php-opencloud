<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('serverlist.inc');
require_once('compute.inc');
require_once('stub_conn.inc');

class ServerListTest extends PHPUnit_Framework_TestCase
{
	private
		$service;
	public function __construct() {
		$conn = new StubConnection('http://example.com','SECRET');
		$this->service = new OpenCloud\Compute(
			$conn,
			'cloudServersOpenStack',
			'DFW',
			'publicURL'
		);
	}

	/**
	 * Tests
	 */
	public function testNext() {
		$obj = new OpenCloud\Compute\Server($this->service);
		$obj->id = 'SERVER-ID';
		$slist = new OpenCloud\Compute\ServerList($this->service, array($obj));
		$n = $slist->Next();
		$this->assertEquals('OpenCloud\Compute\Server',get_class($n));
	}
}
