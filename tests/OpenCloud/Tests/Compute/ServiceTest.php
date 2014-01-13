<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

class ServiceTest extends ComputeTestCase
{

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
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->ServerList());
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
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->NetworkList());
    }

    public function testNamespaces()
    {
        $this->assertNotContains('FOO', $this->service->namespaces());
        $this->assertContains('os-flavor-rxtx', $this->service->namespaces());
    }

}
