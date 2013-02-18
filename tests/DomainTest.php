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
require_once('domain.php');
require_once('stub_conn.php');

class DomainTest extends PHPUnit_Framework_TestCase
{
	private
		$conn, 		// connection
		$domain;	// compute service

	public function __construct() {
		$this->conn = new StubConnection('http://example.com', 'SECRET');
		$dns = new OpenCloud\DNS(
			$this->conn,
			'cloudDNS',
			'N/A',
			'publicURL'
		);
		$this->domain = new OpenCloud\DNS\Domain($dns);
		$this->domain->id = 'DOMAIN-ID';
	}
	/**
	 * Tests
	 */
	public function test__construct() {
		$this->assertEquals(
			'OpenCloud\DNS\Domain',
			get_class($this->domain));
	}
	public function testRecord() {
		$this->assertEquals(
			'OpenCloud\DNS\Record',
			get_class($this->domain->Record()));
	}
	public function testRecordList() {
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($this->domain->RecordList()));
	}
	public function testSubdomain() {
		$this->assertEquals(
			'OpenCloud\DNS\Subdomain',
			get_class($this->domain->Subdomain()));
	}
	public function testSubdomainList() {
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($this->domain->SubdomainList()));
	}
	public function testExport() {
		$this->assertEquals(
			'OpenCloud\DNS\AsyncResponse',
			get_class($this->domain->Export()));
	}
}