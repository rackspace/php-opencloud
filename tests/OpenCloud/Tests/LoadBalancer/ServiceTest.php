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

namespace OpenCloud\Tests\LoadBalancer;

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $service;

    public function setupObjects()
    {
        $this->service = $this->getClient()->loadBalancerService('cloudLoadBalancers', 'DFW', 'publicURL');
    }

    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Service',
            $this->getClient()->loadBalancerService('cloudLoadBalancers', 'DFW', 'publicURL')
        );
    }

    public function testLoadBalancer()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\LoadBalancer',
            $this->service->loadBalancer()
        );
    }

    public function testLoadBalancerList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->loadBalancerList()
        );
    }

    public function testBillableLoadBalancer()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\LoadBalancer',
            $this->service->billableLoadBalancer()
        );
    }

    public function testLoadBillableBalancerList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->BillableLoadBalancerList()
        );
    }

    public function testAllowedDomain()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\AllowedDomain',
            $this->service->allowedDomain()
        );
    }

    public function testAllowedDomainList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->allowedDomainList()
        );
    }

    public function testProtocol()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\Protocol',
            $this->service->protocol()
        );
    }

    public function testProtocolList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->protocolList()
        );
    }

    public function testAlgorithm()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\Algorithm',
            $this->service->algorithm()
        );
    }

    public function testAlgorithmList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->algorithmList()
        );
    }
}
