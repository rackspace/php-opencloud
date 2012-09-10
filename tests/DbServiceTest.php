<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('dbservice.inc');
require_once('stub_conn.inc');

class DbServiceTest extends PHPUnit_Framework_TestCase {
	private
		$dbaas;
	public function __construct() {
		$conn = new StubConnection('http://example.com', 'secret');
		$this->dbaas = new OpenCloud\DbService(
			$conn,
			'cloudDatabases',
			'DFW',
			'publicURL'
		);
	}
	/**
	 * Tests
	 */
	public function test__construct() {
		$this->dbaas = new OpenCloud\DbService(
			new StubConnection('http://example.com', 'secret'),
			'cloudDatabases',
			'DFW',
			'publicURL'
		);
		$this->assertEquals('OpenCloud\DbService', get_class($this->dbaas));
	}
	public function testUrl() {
		$this->assertEquals(
			'https://dfw.databases.api.rackspacecloud.com/v1.0/TENANT-ID/instances',
			$this->dbaas->Url());
		$this->assertEquals(
			'https://dfw.databases.api.rackspacecloud.com/v1.0/TENANT-ID/instances/INSTANCE-ID',
			$this->dbaas->Url('instances/INSTANCE-ID'));
	}
	public function testFlavorList() {
		$this->assertEquals(
			'OpenCloud\Compute\FlavorList',
			get_class($this->dbaas->FlavorList()));
	}
	public function testDbInstance() {
		$inst = $this->dbaas->Instance();
		$this->assertEquals('OpenCloud\DbService\Instance', get_class($inst));
	}
	public function testDbInstanceList() {
		$list = $this->dbaas->InstanceList();
		$this->assertEquals(
		    'OpenCloud\DbService\InstanceList',
		    get_class($list));
	}
}
