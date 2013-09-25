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

use PHPUnit_Framework_TestCase;
use OpenCloud\Tests\StubConnection;
use OpenCloud\LoadBalancer\Service;

class ServiceTest extends PHPUnit_Framework_TestCase
{

    private $conn;
    private $service;

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new Service(
            $this->conn, 'cloudLoadBalancers', array('DFW'), 'publicURL'
        );
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->service = new Service(
            $this->conn, 'cloudLoadBalancers', array('DFW'), 'publicURL'
        );
        $this->assertInstanceOf('OpenCloud\LoadBalancer\Service', $this->service);
    }

    public function testUrl()
    {
        $url = $this->service->url();
        $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers');
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
