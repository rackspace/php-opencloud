<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('rackspace.inc');

/**
 * Stub for Rackspace to Override the ->Request() method
 */
class MyRackspace extends OpenCloud\Rackspace {
    public function Request() {
    	return new OpenCloud\BlankResponse(array(
    		'body'=>file_get_contents(TESTDIR.'/connection.json')));
    }
}

class RackspaceTest extends PHPUnit_Framework_TestCase
{
	private
		$conn;
	public function __construct() {
		$this->conn = new MyRackspace('http://example.com', 'secret');
	}

	/**
	 * Tests
	 */
	public function test_credentials() {
		$cred = $this->conn->credentials();
		$this->assertRegExp('/RAX-KSKEY:apiKeyCredentials/', $cred);
	}
	public function testDbService() {
		$dbaas = $this->conn->DbService(NULL, 'DFW');
		$this->assertEquals('OpenCloud\DbService', get_class($dbaas));
	}
}
