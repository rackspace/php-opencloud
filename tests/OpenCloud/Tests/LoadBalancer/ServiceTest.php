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

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use OpenCloud\Rackspace;
use OpenCloud\Tests\MockSubscriber;

class ServiceTest extends LoadBalancerTestCase
{
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

    public function test_Listing_Load_Balancers()
    {
        // Load JSON HTTP data
        $authData = file_get_contents($this->getTestFilePath('Auth', './'));
        $data1 = file_get_contents($this->getTestFilePath('LoadBalancers1'));
        $data2 = file_get_contents($this->getTestFilePath('LoadBalancers2'));
        $data3 = file_get_contents($this->getTestFilePath('LoadBalancers3'));

        // Populate mock response queue
        $mock = new MockPlugin();
        $mock->addResponse(Response::fromMessage($authData))
            ->addResponse(Response::fromMessage($data1))
            ->addResponse(Response::fromMessage($data2))
            ->addResponse(Response::fromMessage($data3));

        // We need to define our own setup because *jazz hands*
        $client = $this->newClient();
        $client->addSubscriber($mock);
        $service = $client->loadBalancerService(null, 'IAD');

        // Ensure that a series of paginated calls return a holistic collection
        $lbs = $service->loadBalancerList(false, array('limit' => 2));
        $ids = array();
        foreach ($lbs as $lb) {
            $ids[] = $lb->id;
        }

        // Check our assumptions
        $this->isCollection($lbs);
        $this->assertEquals($ids, array(1, 2, 3, 4, 5));
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
