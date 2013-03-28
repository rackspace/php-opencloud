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

require_once('dns.php');
require_once('server.php');
require_once('stub_conn.php');

class PtrRecordTest extends PHPUnit_Framework_TestCase
{
	private
		$conn,
		$dns,
		$record;	// the record

	public function __construct() {
		$this->conn = new StubConnection('http://example.com', 'SECRET');
		$this->dns = new OpenCloud\DNS(
			$this->conn,
			'cloudDNS',
			'N/A',
			'publicURL'
		);
		$this->record = new OpenCloud\DNS\PtrRecord($this->dns);
	}
	/**
	 * Tests
	 */
	public function test__construct() {
		$this->record = new OpenCloud\DNS\PtrRecord($this->dns);
		$this->assertEquals(
			'PTR',
			$this->record->type);
	}
	/**
	 * @expectedException OpenCloud\DNS\RecordTypeError
	 */
	public function test__construct2() {
		// not allowed to change the record type from PTR
		$this->record = new OpenCloud\DNS\PtrRecord(
			$this->dns, array('type' => 'A'));
	}
	public function testUrl() {
		$this->assertEquals(
			'https://dns.api.rackspacecloud.com/v1.0/TENANT-ID/rdns',
			$this->record->Url());
	}
	public function testCreate() {
		$server = $this->conn->Compute(NULL, 'ORD')->Server(array('id'=>'foo'));
		$this->assertEquals(
			'OpenCloud\DNS\AsyncResponse',
			get_class($this->record->Create(array(), $server)));
	}
	public function testUpdate() {
		$server = $this->conn->Compute(NULL, 'ORD')->Server(array('id'=>'foo'));
		$this->assertEquals(
			'OpenCloud\DNS\AsyncResponse',
			get_class($this->record->Update(array(), $server)));
	}
	public function testDelete() {
		$server = $this->conn->Compute(NULL, 'ORD')->Server(array('id'=>'foo'));
		$this->assertEquals(
			'OpenCloud\DNS\AsyncResponse',
			get_class($this->record->Delete($server)));
	}
}