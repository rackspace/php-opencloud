<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version   1.0.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
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
    public function test_Deprecated_Endpoint_Throws_Exception()
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

    /**
     * @expectedException OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function testV1Dot0IsNotSupported()
    {
        $this->addMockSubscriber($this->getTestFilePath('Catalog_Compute_V1.0'));
        $this->getClient()->authenticate();

        $this->getClient()->computeService(null, 'DFW');
    }
      
    public function testV1Dot1IsSupported()
    {
        $this->addMockSubscriber($this->getTestFilePath('Catalog_Compute_V1.1'));
        $this->getClient()->authenticate();

        $computeService = $this->getClient()->computeService(null, 'DFW');
        $this->assertStringStartsWith('/v1.1', $computeService->getUrl()->getPath());
    }
}
