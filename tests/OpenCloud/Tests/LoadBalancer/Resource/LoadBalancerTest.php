<?php

namespace OpenCloud\Tests\LoadBalancer\Resource;

use OpenCloud\Tests\LoadBalancer\LoadBalancerTestCase;

class LoadBalancerTest extends LoadBalancerTestCase
{

    /**
     * @expectedException OpenCloud\Common\Exceptions\DomainError
     */
    public function test_Add_Node()
    {
        $lb = $this->service->LoadBalancer();
        $lb->addNode('1.1.1.1', 80);
        
        $this->assertEquals('1.1.1.1', $lb->nodes[0]->address);
        
        // this should trigger an error
        $lb->AddNode('1.1.1.2', 80, 'foobar');
    }

    public function test_Remove_Node()
    {
        $lb = $this->service->LoadBalancer();
        
        $lb->addNode('1.1.1.1', 80);
        $lb->addNodes();
        
        $lb->removeNode(1040);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\MissingValueError
     */
    public function test_Adding_Nodes_Fails_When_Empty()
    {
        $this->service->loadBalancer()->addNodes();
    }
    
    /**
     * @ expectedException OpenCloud\Common\Exceptions\DomainError
     */
    public function testAddVirtualIp()
    {
        $lb = $this->service->loadBalancer();
        $lb->addVirtualIp('public');
        $this->assertEquals('PUBLIC', $lb->virtualIps[0]->type);
    }

    public function testNode()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/nodes/321',
            (string) $this->loadBalancer->node('321')->getUrl()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\LoadBalancer',
            $this->loadBalancer->Node('345')->getParent()
        );
        
        $this->assertEquals(
            'OpenCloud\LoadBalancer\Resource\Node[456]',
            (string) $this->loadBalancer->Node('456')->Name()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\Metadata',
            $this->loadBalancer->Node('456')->Metadata()
        );
        
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->loadBalancer->Node('456')->MetadataList()
        );
        
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/nodes/456',
            (string) $this->loadBalancer->Node('456')->getUrl()
        );
    }

    public function testNodeList()
    {
        $lb = $this->service->LoadBalancer();
        $lb->addVirtualIp('PUBLIC', 4);
        $lb->addNode('0.0.0.1', 1000);
        $lb->create(array('name' => 'foobar'));
        $this->assertInstanceOf(self::COLLECTION_CLASS, $lb->NodeList());
    }

    public function testNodeEvent()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/nodes/events',
            (string) $this->loadBalancer->NodeEvent()->Url()
        );
    }

    public function testNodeEventList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->loadBalancer->NodeEventList());
    }

    public function testVirtualIp()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/virtualips',
            (string) $this->loadBalancer->VirtualIp()->Url()
        );
    }

    public function testVirtualIpList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->loadBalancer->virtualIpList());
    }

    public function testSessionPersistence()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/sessionpersistence',
            (string) $this->loadBalancer->SessionPersistence()->Url()
        );
    }

    public function testErrorPage()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/errorpage',
            (string) $this->loadBalancer->ErrorPage()->Url()
        );
    }

    public function testHealthMonitor()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/healthmonitor',
            (string) $this->loadBalancer->HealthMonitor()->Url()
        );
    }

    public function testStats()
    {
        $this->loadBalancer->id = 1024;

        $x = $this->loadBalancer->stats();
        $this->assertInstanceOf('OpenCloud\LoadBalancer\Resource\Stats', $x);
    }

    public function testUsage()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/usage',
            (string) $this->loadBalancer->Usage()->Url()
        );
    }

    public function testAccess()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/accesslist',
            (string) $this->loadBalancer->Access()->Url()
        );
    }

    public function testAccessList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->loadBalancer->AccessList()
        );
    }

    public function testConnectionThrottle()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/connectionthrottle',
            (string) $this->loadBalancer->ConnectionThrottle()->Url()
        );
    }

    public function testConnectionLogging()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/connectionlogging',
            (string) $this->loadBalancer->ConnectionLogging()->Url()
        );
    }

    public function testContentCaching()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/contentcaching',
            (string) $this->loadBalancer->ContentCaching()->Url()
        );
    }

    public function testSSLTermination()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/ssltermination',
            (string) $this->loadBalancer->SSLTermination()->Url()
        );
    }

    public function testMetadata()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/metadata',
            (string) $this->loadBalancer->Metadata()->Url()
        );
    }

    public function testMetadataList()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->loadBalancer->MetadataList()
        );
    }

    public function testUpdate()
    {
        $lb = $this->service->LoadBalancer();

        $resp = $lb->Update(array(
            'algorithm' => 'ROUND_ROBIN',
            'protocol' => 'HTTP',
            'port' => '8080'
        ));

        $this->assertNotNull($resp->getStatusCode());

        $this->assertEquals('ROUND_ROBIN',$lb->algorithm);
        $this->assertEquals('HTTP',$lb->protocol);
        $this->assertEquals('8080',$lb->port);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Update_Fails_Without_Correct_Fields()
    {
        $this->loadBalancer->update(array('foo' => 'bar'));
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