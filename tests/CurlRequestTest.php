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

require_once('http.inc');

class CurlRequestTest extends PHPUnit_Framework_TestCase
{
    private
        $http;
    public function __construct() {
        $this->http = new OpenCloud\CurlRequest('php://memory');
    }
    /**
     * Tests
     */
	public function test__construct() {
	    $this->assertEquals(TRUE,TRUE);
    }
	public function testSetOption() {
	    $this->http->SetOption(CURLOPT_RETURNTRANSFER, TRUE);
	    $this->assertEquals(TRUE, TRUE);
    }
	public function testSetConnectTimeout() {
	    $this->http->SetConnectTimeout(99);
	    $this->assertEquals(TRUE, TRUE);
    }
	public function testSetHttpTimeout() {
	    $this->http->SetHttpTimeout(99);
	    $this->assertEquals(TRUE, TRUE);
    }
	public function testSetRetries() {
	    $this->http->SetRetries(99);
	    $this->assertEquals(TRUE, TRUE);
    }
	public function testsetheaders() {
	    $this->http->SetHeaders(array('X-Status','Dumb'));
	    $this->assertEquals(TRUE, TRUE);
    }
	public function testSetHeader() {
	    $this->http->SetHeader('X-Status', 'Ok');
	    $this->assertEquals(TRUE, TRUE);
    }
    /**
     * @expectedException OpenCloud\HttpError
     */
	public function testExecute() {
	    $obj = $this->http->Execute();
	    $this->assertEquals('Foo', get_class($obj));
    }
	public function testinfo() {
	    $this->assertEquals(TRUE, is_array($this->http->info()));
    }
	public function testerrno() {
	    $this->assertEquals(0, $this->http->errno());
    }
	public function testerror() {
	    $this->assertEquals(
	        '',
	        $this->http->error());
    }
	public function testclose() {
	    $this->assertEquals(NULL, $this->http->close());
    }
}
