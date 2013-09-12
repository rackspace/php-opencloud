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

namespace OpenCloud\Tests\Common;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Request\Curl;

class CurlRequestTest extends PHPUnit_Framework_TestCase
{

    private $http;

    public function __construct()
    {
        $this->http = new Curl('php://memory');
    }

    public function testSetOption()
    {
        $this->http->setOption(CURLOPT_RETURNTRANSFER, TRUE);
        $this->assertEquals(TRUE, TRUE);
    }

    public function testSets()
    {
        $this->http->setConnectTimeout(99);
        $this->http->setHttpTimeout(99);
        $this->http->setRetries(99);
        $this->http->setHeaders(array('X-Status', 'Dumb'));
        $this->http->setHeader('X-Status', 'Ok');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpError
     */
    public function testExecute()
    {
        $obj = $this->http->Execute();
        $this->assertEquals('Foo', get_class($obj));
    }

    public function testinfo()
    {
        $this->assertTrue(is_array($this->http->info()));
    }

    public function testerrno()
    {
        $this->assertEquals(0, $this->http->errno());
    }

    public function testerror()
    {
        $this->assertEmpty($this->http->error());
    }

    public function testclose()
    {
        $this->assertNull($this->http->close());
    }

}
