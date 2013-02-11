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

require_once('dbservice.php');
require_once('stub_conn.php');

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
			'OpenCloud\Collection',
			get_class($this->dbaas->FlavorList()));
	}
	public function testDbInstance() {
		$inst = $this->dbaas->Instance();
		$this->assertEquals('OpenCloud\DbService\Instance', get_class($inst));
	}
	public function testDbInstanceList() {
		$list = $this->dbaas->InstanceList();
		$this->assertEquals(
		    'OpenCloud\Collection',
		    get_class($list));
	}
}
