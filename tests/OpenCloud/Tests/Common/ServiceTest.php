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

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;

    public function __construct()
    {
        $this->service = $this->getClient()->compute('cloudServersOpenStack', 'DFW');
    }

    /**
     * Tests
     */
    public function testUrl()
    {
        /* This also validates the private function get_endpoint() */
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID', $this->service->Url());
    }

    public function testUrl2()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/sub?a=1&b=2', 
            $this->service->Url('sub', array('a' => 1, 'b' => 2))
        );
    }

    public function testRequest()
    {
        // this returns 404 because the Connection::Request() function
        // is stubbed out
        $resp = $this->service->Request('http://example.com');
        $this->assertEquals(404, $resp->HttpStatus());
    }

    public function testExtensions()
    {
        $ext = $this->service->Extensions();
        $this->assertTrue(is_array($ext));
    }

    public function testLimits()
    {
        $this->assertTrue(is_array($this->service->Limits()));
    }

    public function testRegion()
    {
        $this->assertEquals('DFW', $this->service->region());
    }

    public function testName()
    {
        $this->assertEquals('cloudServersOpenStack', $this->service->name());
    } 
    
    public function test_UrlType_Defaults()
    {
        $service = new MyService(
            $this->getClient(), 'compute', 'cloudServersOpenStack', 'DFW', ''
        );
    }
    
}
