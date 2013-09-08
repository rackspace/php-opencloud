<?php
/**
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests;

use PHPUnit_Framework_TestCase;
use OpenCloud\LoadBalancer\Resources\SubResource;
use OpenCloud\LoadBalancer\Resources\LoadBalancer;
use OpenCloud\LoadBalancer\Service;
use OpenCloud\Tests\StubConnection;

class MySubResource extends SubResource
{

    public static $json_name = 'ignore';
    public static $url_resource = 'ignore';
    
    protected $createKeys = array('id');

    public function CreateJson()
    {
        return parent::CreateJson();
    }

    public function UpdateJson($params = array())
    {
        return parent::UpdateJson($params);
    }

}

class LoadBalancerTest extends PHPUnit_Framework_TestCase
{

    private $connection;
    private $service;
    private $loadBalancer;

    public function __construct()
    {
        $this->connection = new StubConnection('http://example.com', 'SECRET');
        $this->service = new Service(
            $this->connection, 'cloudLoadBalancers', 'DFW', 'publicURL'
        );
        $this->loadBalancer = new LoadBalancer($this->service);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DomainError
     */
    public function testAddNode()
    {
        $lb = $this->service->LoadBalancer();
        $lb->addNode('1.1.1.1', 80);
        
        $this->assertEquals('1.1.1.1', $lb->nodes[0]->address);
        
        // this should trigger an error
        $lb->AddNode('1.1.1.2', 80, 'foobar');
    }

    public function testAddNodes()
    {
        $lb = $this->service->LoadBalancer();
        //$lb->Create();
        $lb->AddNode('1.1.1.1', 80);
        $lb->Create();
        $lb->AddNodes();
        
    }

    /**
     * @ expectedException \OpenCloud\Common\Exceptions\DomainError
     */
    public function testAddVirtualIp()
    {
        $lb = $this->service->LoadBalancer();
        $lb->AddVirtualIp('public');
        $this->assertEquals('PUBLIC', $lb->virtualIps[0]->type);
    }

    public function testNode()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/nodes/321', 
            $lb->Node('321')->Url()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resources\LoadBalancer', 
            $lb->Node('345')->Parent()
        );
        
        $this->assertEquals(
            'OpenCloud\LoadBalancer\Resources\Node[456]', 
            $lb->Node('456')->Name()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resources\Metadata', 
            $lb->Node('456')->Metadata()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $lb->Node('456')->MetadataList()
        );
        
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/nodes/456', 
            $lb->Node('456')->Url()
        );
    }

    public function testNodeList()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $lb->NodeList());
    }

    public function testNodeEvent()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/nodes/events', 
            $lb->NodeEvent()->Url()
        );
    }

    public function testNodeEventList()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $lb->NodeEventList());
    }

    public function testVirtualIp()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/virtualips', 
            $lb->VirtualIp()->Url()
        );
    }

    public function testVirtualIpList()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $lb->VirtualIpList());
    }

    public function testSessionPersistence()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/sessionpersistence', 
            $lb->SessionPersistence()->Url()
        );
    }

    public function testErrorPage()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/errorpage', 
            $lb->ErrorPage()->Url()
        );
    }

    public function testHealthMonitor()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/healthmonitor', 
            $lb->HealthMonitor()->Url()
        );
    }

    public function testStats()
    {
        $this->loadBalancer->id = 1024;
        $x = $this->loadBalancer->Stats();
        $this->assertInstanceOf('OpenCloud\LoadBalancer\Resources\Stats', $x);
        $this->assertEquals(10, $x->connectTimeOut);
    }

    public function testUsage()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/usage', 
            $lb->Usage()->Url()
        );
    }

    public function testAccess()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/accesslist', 
            $lb->Access()->Url()
        );
    }

    public function testAccessList()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $lb->AccessList()
        );
    }

    public function testConnectionThrottle()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/connectionthrottle', 
            $lb->ConnectionThrottle()->Url()
        );
    }

    public function testConnectionLogging()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/connectionlogging', 
            $lb->ConnectionLogging()->Url()
        );
    }

    public function testContentCaching()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/contentcaching', 
            $lb->ContentCaching()->Url()
        );
    }

    public function testSSLTermination()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/ssltermination', 
            $lb->SSLTermination()->Url()
        );
    }

    public function testMetadata()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
            'loadbalancers/123/metadata', 
            $lb->Metadata()->Url()
        );
    }

    public function testMetadataList()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $lb->MetadataList()
        );
    }

    public function testSubResource()
    {
        $this->loadBalancer->id = '42';
        
        $sub = new MySubResource($this->service, '42');
        $sub->setParent($this->loadBalancer);
        
        $this->assertEquals('OpenCloud\Tests\MySubResource', get_class($sub));
        
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/42/ignore', 
            $sub->Url('foo', array('one' => 1)
        ));
        
        $obj = $sub->UpdateJson();
        $json = json_encode($obj);
        
        $this->assertEquals('{"ignore":{"id":"42"}}', $json);
        
        $this->assertEquals($this->loadBalancer, $sub->getParent());
        
        $this->assertEquals('OpenCloud\Tests\MySubResource-42', $sub->Name());
    }
    
    public function testAddingNodeWithType()
    {
        $this->loadBalancer->addNode('localhost', 8080, 'ENABLED', 'PRIMARY', 10);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DomainError
     */
    public function testAddingNodeFailsWithoutCorrectType()
    {
        $this->loadBalancer->addNode('localhost', 8080, 'ENABLED', 'foo');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DomainError
     */
    public function testAddingNodeFailsWithoutCorrectWeight()
    {
        $this->loadBalancer->addNode('localhost', 8080, 'ENABLED', 'PRIMARY', 'baz');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\MissingValueError
     */
    public function testAddingNodesFailsWithoutAnythingPreviouslySet()
    {
        $this->loadBalancer->addNodes();
    }
    
    public function testAddingVirtualIp()
    {
        $this->loadBalancer->id = 2000;
        $this->loadBalancer->addVirtualIp(123, 4);
        $this->loadBalancer->addVirtualIp('PUBLIC', 6);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DomainError
     */
    public function testAddingVirtualIpFailsWithIncorrectIpType()
    {
        $this->loadBalancer->addVirtualIp(123, 5);
    }

}
