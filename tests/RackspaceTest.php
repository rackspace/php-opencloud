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
