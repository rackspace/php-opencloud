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

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $service;
    
    public function __construct()
    {
        $this->service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW', 'publicURL');
    }

    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service', 
            $this->getClient()->computeService('cloudServersOpenStack', 'DFW')
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function test_Deprecated_Endpoint()
    {
        $this->getClient()->computeService('cloudServers', 'DFW');
    }

    public function testServer()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Server', $this->service->Server());
    }

    public function testServerList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->service->ServerList());
    }

    public function testImage()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Image', $this->service->Image());
    }

    public function testNetwork()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Network', $this->service->Network());
    }

    public function testNetworkList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->service->NetworkList());
    }

    public function testNamespaces()
    {
        $this->assertNotContains('FOO', $this->service->namespaces());
        $this->assertContains('rax-bandwidth', $this->service->namespaces());
    }

    public function test_load_namespaces()
    {
        $this->assertContains('rax-bandwidth', $this->service->namespaces());
    }

}
