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

namespace OpenCloud\Tests\ObjectStore;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Request\Response\Blank;
use OpenCloud\ObjectStore\Service;
use OpenCloud\Tests\StubConnection;

/**
 * Stub wrapper class so that we can override the request() method
 */
class MyObjectStore extends Service
{

    public function request($url, $method = 'GET', array $headers = array(), $body = null)
    {
        return new Blank;
    }

}

class ObjectStoreTest extends PHPUnit_Framework_TestCase
{

    private $ostore;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->ostore = new MyObjectStore(
            $conn, 'cloudFiles', array('DFW'), 'publicURL'
        );
    }

    public function test__construct()
    {
        $this->assertTrue(is_object($this->ostore));
        $this->assertInstanceOf('OpenCloud\Tests\ObjectStore\MyObjectStore', $this->ostore);
    }

    public function testUrl()
    {
        $urls = $this->ostore->url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID');
        }
    }

    public function testContainer()
    {
        $obj = $this->ostore->Container();
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $obj);
    }

    public function testContainerList()
    {
        $clist = $this->ostore->ContainerList();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $clist);
    }

    public function testSetTempUrlSecret()
    {
        $resp = $this->ostore->setTempUrlSecret('foobar');
        $this->assertEquals(200, $resp->httpStatus());
    }

    public function testCDN()
    {
        $this->assertInstanceOf('OpenCloud\ObjectStore\CDNService', $this->ostore->CDN());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnError
     */
    public function testCDNCDN()
    {
        $this->assertFalse(get_class($this->ostore->CDN()->CDN()));
    }

}
