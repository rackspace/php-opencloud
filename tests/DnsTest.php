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
require_once('stub_conn.php');

class DnsTest extends PHPUnit_Framework_TestCase
{
	private
		$conn, 		// connection
		$dns;	// compute service

	public function __construct() {
		$this->conn = new StubConnection('http://example.com', 'SECRET');
		$this->dns = new OpenCloud\DNS(
			$this->conn,
			'cloudDNS',
			'N/A',
			'publicURL'
		);
	}
	/**
	 * Tests
	 */
	public function test__construct() {
	    $thing = new OpenCloud\DNS($this->conn,'cloudDNS','N/A','publicURL');
	    $this->assertEquals(
	        'OpenCloud\DNS',
	        get_class($thing));
	}
	public function testUrl() {
		$this->assertEquals(
			'https://dns.api.rackspacecloud.com/v1.0/TENANT-ID',
			$this->dns->Url());
	}
	public function testDomain() {
		$this->assertEquals(
			'OpenCloud\DNS\Domain',
			get_class($this->dns->Domain()));
	}
	public function testDomainList() {
		$list = $this->dns->DomainList();
		$this->assertEquals(
			'OpenCloud\Collection',
			get_class($list));
		$this->assertGreaterThan(
			2,
			strlen($list->Next()->Name()));
	}
	/**
	 * @expectedException OpenCloud\DNS\AsyncHttpError
	 */
	public function testAsyncRequest() {
	    $resp = $this->dns->AsyncRequest('FOOBAR');
	}
	public function testImport() {
		$this->assertEquals(
			'OpenCloud\DNS\AsyncResponse',
			get_class($this->dns->Import('foo bar oops')));
	}
}