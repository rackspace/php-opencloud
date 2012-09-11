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

require_once('http.inc');

define('TESTDATA',<<<ENDTESTDATA
Four score and seven years ago, our
fathers brought forth on this continent
a new nation, conceived in Liberty, and
dedicated to the proposition that all
men were created equal.
ENDTESTDATA
);

// stub for request
class MyStubRequest extends OpenCloud\CurlRequest {
    public function info() {
        parent::info();
        return array('http_code'=>'200');
    }
    public function errno() {
        parent::errno();
        return 0;
    }
    public function error() {
        parent::error();
        return 'NOPE';
    }
    public function ReturnHeaders() {
    	return array(
    		'HTTP/1.1 200 OK',
    		'Content-Type: text/plain',
    		'X-Test-Header: Nothing'
    	);
    }
}

class HttpResponseTest extends PHPUnit_Framework_TestCase
{
    private
        $response;
    public function __construct() {
        $request = new MyStubRequest('file:/dev/null');
        $this->response = new OpenCloud\HttpResponse(
            $request,
            TESTDATA);
    }
    /**
     * Tests
     */
    public function test__construct() {
    	$req = new OpenCloud\CurlRequest('file:/dev/null');
    	$req->SetOption(CURLOPT_RETURNTRANSFER, TRUE);
    	$req->SetConnectTimeout(20);
    	$req->SetHttpTimeout(20);
    	$req->SetRetries(2);
    	$req->setheaders(array());
    	$req->SetHeader('X-Transfer-Name', 'Glen Campbell');
    	$req->Execute();
    	$req->info();
    	$req->errno();
    	$req->error();
    	$req->ReturnHeaders();
    	$req->_get_header_cb(curl_init('http://example.com'), 'X-Status: Blame');
    	$this->response = new OpenCloud\HttpResponse(
    		$req,
    		TESTDATA);
        $this->assertGreaterThan(0, count($this->response->Headers()));
    	$this->assertEquals('', $req->close());
    }
    public function testHttpBody() {
        $this->assertEquals('Four', substr($this->response->HttpBody(), 0, 4));
    }
    public function testHeaders() {
        $this->assertEquals(3, sizeof($this->response->Headers()));
    }
    public function testHeader() {
        $this->assertEquals(
            'Nothing',
            $this->response->Header('X-Test-Header'));
    }
    public function testinfo() {
        $this->assertEquals(TRUE, is_array($this->response->info()));
    }
    public function testerrno() {
        $this->assertEquals(0, $this->response->errno());
    }
    public function testerror() {
        $this->assertEquals('NOPE', $this->response->error());
    }
    public function testHttpStatus() {
        $this->assertEquals(200, $this->response->HttpStatus());
    }
}
