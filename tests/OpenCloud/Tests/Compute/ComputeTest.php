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

namespace OpenCloud\Tests\Compute;

use OpenCloud\Compute\Service;
use OpenCloud\Tests\StubConnection;
use PHPUnit_Framework_TestCase;

class ServiceTest extends PHPUnit_Framework_TestCase
{

    private $conn;    // connection
    private $compute; // compute service

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->compute = new Service(
            $this->conn, 'cloudServersOpenStack', array('DFW'), 'publicURL'
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function test__construct()
    {
        $compute = new Service(
            $this->conn, 'cloudServers', array('DFW'), 'publicURL'
        );
    }

    public function testUrl()
    {
        $url = $this->compute->Url();
        $this->assertEquals($url, 'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/servers');
        
        $url = $this->compute->Url('servers/detail');
        $this->assertEquals($url, 'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/servers/detail');
        
        $url = $this->compute->Url('servers', array('A' => 1, 'B' => 2));
        $this->assertEquals($url, 'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/servers?A=1&B=2');
    }

    public function testServer()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Server', $this->compute->Server());
    }

    public function testServerList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->compute->ServerList());
    }

    public function testImage()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Image', $this->compute->Image());
    }

    public function testNetwork()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Network', $this->compute->Network());
    }

    public function testNetworkList()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', $this->compute->NetworkList());
    }

    public function testNamespaces()
    {
        $this->assertNotContains('FOO', $this->compute->namespaces());
        $this->assertContains('rax-bandwidth', $this->compute->namespaces());
    }

    public function test_load_namespaces()
    {
        $this->assertContains('rax-bandwidth', $this->compute->namespaces());
    }

}
