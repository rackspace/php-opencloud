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

namespace OpenCloud\Tests;

define('TESTDATA', <<<ENDTESTDATA
Four score and seven years ago, our
fathers brought forth on this continent
a new nation, conceived in Liberty, and
dedicated to the proposition that all
men were created equal.
ENDTESTDATA
);

use OpenCloud\Common\Request\Curl;
use OpenCloud\Common\Request\Response\Http;
use PHPUnit_Framework_TestCase;

// stub for request
class MyStubRequest extends Curl
{

    public function info()
    {
        parent::info();
        return array('http_code' => '200');
    }

    public function errno()
    {
        parent::errno();
        return 0;
    }

    public function error()
    {
        parent::error();
        return 'NOPE';
    }

    public function returnHeaders()
    {
        return array(
            "HTTP/1.1 200 OK\r\n",
            "Content-Type: text/plain\r\n",
            "X-Test-Header: Nothing\r\n"
        );
    }

}

class HttpResponseTest extends PHPUnit_Framework_TestCase
{

    private $response;
    private $nullFile;

    public function __construct()
    {
        $this->nullFile = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'NUL' : '/dev/null';

        $request = new MyStubRequest('file:' . $this->nullFile);
        $this->response = new Http($request, TESTDATA);
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        define('RAXSDK_CACERTPEM', __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem');
        
        $request = new Curl('file:' . $this->nullFile, 'HEAD', array(
            CURLOPT_CERTINFO => true
        ));
        $request->setOption(CURLOPT_RETURNTRANSFER, TRUE);
        $request->setConnectTimeout(20);
        $request->setHttpTimeout(20);
        $request->setRetries(2);
        $request->setheaders(array());
        $request->setHeader('X-Transfer-Name', 'Glen Campbell');
        $request->execute();
        $request->info();
        $request->errno();
        $request->error();
        $request->returnHeaders();
        $request->_get_header_cb(curl_init('http://example.com'), 'X-Status: Blame');
        $request->_get_header_cb(curl_init('http://example.com'), 'Test-Header');

        $this->response = new Http($request, TESTDATA);
        $this->assertGreaterThan(0, count($this->response->Headers()));
        $this->assertEquals('', $request->close());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpError
     */
    public function testSettingHeadersFailWithoutArray()
    {
        $request = new Curl('file:' . $this->nullFile);
        $request->setheaders(null);
    }

    public function testHttpBody()
    {
        $this->assertEquals('Four', substr($this->response->HttpBody(), 0, 4));
    }

    public function testHeaders()
    {
        $harr = $this->response->headers();
        $this->assertCount(3, $harr);
        
        foreach ($harr as $value) {
            $this->assertEquals(trim($value), $value);
        }
    }

    public function testHeader()
    {
        $this->assertEquals(
            'Nothing', $this->response->Header('X-Test-Header')
        );
    }

    public function testinfo()
    {
        $this->assertEquals(true, is_array($this->response->info()));
    }

    public function testerrno()
    {
        $this->assertEquals(0, $this->response->errno());
    }

    public function testerror()
    {
        $this->assertEquals('NOPE', $this->response->error());
    }

    public function testHttpStatus()
    {
        $this->assertEquals(200, $this->response->HttpStatus());
    }

}
