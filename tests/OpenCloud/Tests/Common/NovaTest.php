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
use OpenCloud\Common\Nova;
use OpenCloud\Tests\StubConnection;

class MyNova extends Nova {}

class NovaTest extends PHPUnit_Framework_TestCase
{

    private $conn;
    private $nova;

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->nova = new MyNova(
            $this->conn, 'compute', 'cloudServersOpenStack', 'DFW', 'publicURL'
        );
    }

    /**
     * Tests
     */
    public function testUrl()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/foo', $this->nova->Url('foo'));
    }

    public function testFlavor()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Flavor', $this->nova->flavor());
    }

    public function testFlavorList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->nova->flavorList());
    }

    public function testRequest()
    {
        // this returns 404 because the Connection::Request() is stubbed out
        $resp = $this->nova->Request('http://example.com');
        $this->assertEquals(404, $resp->HttpStatus());
    }

}
