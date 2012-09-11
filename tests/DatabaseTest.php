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
require_once('database.inc');
require_once('dbservice.inc');

class DatabaseTest extends PHPUnit_Framework_TestCase
{
	private
		$inst,
		$db;
	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$dbaas = new OpenCloud\DbService(
		    $conn, 'cloudDatabases', 'DFW', 'publicURL');
		$this->inst = new OpenCloud\DbService\Instance($dbaas);
		$this->inst->id = '12345678';
		$this->db = new OpenCloud\DbService\Database($this->inst);
	}
	/**
	 * Tests
	 */
	public function test__construct() {
		$this->assertEquals(
			'OpenCloud\DbService\Database',
			get_class(new OpenCloud\DbService\Database($this->inst)));
	}
	public function testUrl() {
		$this->db->name = 'TEST';
		$this->assertEquals(
			'https://dfw.databases.api.rackspacecloud.com/v1.0/'.
			    'TENANT-ID/instances/12345678/databases/TEST',
			$this->db->Url());
	}
	public function testInstance() {
		$this->assertEquals(
			'OpenCloud\DbService\Instance',
			get_class($this->db->Instance()));
	}
	public function testCreate() {
		$resp = $this->db->Create(array('name'=>'FOOBAR'));
		$this->assertEquals(
			'OpenCloud\BlankResponse',
			get_class($resp));
	}
	/**
	 * @expectedException OpenCloud\DbService\DatabaseUpdateError
	 */
	public function testUpdate() {
		$this->db->Update();
	}
	public function testDelete() {
		$this->db->name = 'FOOBAR';
		$this->assertEquals(
			'OpenCloud\BlankResponse',
			get_class($this->db->Delete()));
	}
}
