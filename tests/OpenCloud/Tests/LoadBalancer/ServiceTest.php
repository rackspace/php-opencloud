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

namespace OpenCloud\Tests\LoadBalancer;

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    
    private $service;

    public function __construct()
    {
        $this->service = $this->getClient()->loadBalancerService('cloudLoadBalancers', 'DFW', 'publicURL');
    }

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\LoadBalancer\Service', $this->service);
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
            'OpenCloud\Common\Collection', 
            $this->service->loadBalancerList()
        );
    }

    public function testBillableLoadBalancer()
    {
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\BillableLoadBalancer', 
            $this->service->billableLoadBalancer()
        );
    }

    public function testLoadBillableBalancerList()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
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
            'OpenCloud\Common\Collection', 
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
            'OpenCloud\Common\Collection', 
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
            'OpenCloud\Common\Collection', 
            $this->service->algorithmList()
        );
    }

}
