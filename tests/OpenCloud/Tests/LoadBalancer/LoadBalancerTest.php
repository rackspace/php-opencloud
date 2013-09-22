<?php
/**
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\LoadBalancer;

use PHPUnit_Framework_TestCase;
use OpenCloud\LoadBalancer\Resource\SubResource;
use OpenCloud\LoadBalancer\Resource\LoadBalancer;
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
            $this->connection, 'cloudLoadBalancers', array('DFW'), 'publicURL'
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

    public function testRemoveNode()
    {
        $lb = $this->service->LoadBalancer(2000);
        $resp = $lb->removeNode(1041);
        $this->assertEquals(202,$resp->status);
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
        
        $urls = $lb->Node('321')->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/nodes/321');
        }
        
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\LoadBalancer', 
            $lb->Node('345')->Parent()
        );
        
        $this->assertEquals(
            'OpenCloud\LoadBalancer\Resource\Node[456]', 
            $lb->Node('456')->Name()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\Metadata', 
            $lb->Node('456')->Metadata()
        );
        
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $lb->Node('456')->MetadataList()
        );
        
        $urls = $lb->Node('456')->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/nodes/456');
        }
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
        $urls = $lb->NodeEvent()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/nodes/events');
        }
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
        $urls = $lb->VirtualIp()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/virtualips');
        }
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
        $urls = $lb->SessionPersistence()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/123/sessionpersistence');
        }
    }

    public function testErrorPage()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->ErrorPage()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/errorpage');
        }
    }

    public function testHealthMonitor()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->HealthMonitor()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/healthmonitor');
        }
    }

    public function testStats()
    {
        $this->loadBalancer->id = 1024;
        $x = $this->loadBalancer->Stats();
        $this->assertInstanceOf('OpenCloud\LoadBalancer\Resource\Stats', $x);
        $this->assertEquals(10, $x->connectTimeOut);
    }

    public function testUsage()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->Usage()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/usage');
        }
    }

    public function testAccess()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->Access()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/accesslist');
        }
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
        $urls = $lb->ConnectionThrottle()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/connectionthrottle');
        }
    }

    public function testConnectionLogging()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->ConnectionLogging()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/connectionlogging');
        }
    }

    public function testContentCaching()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->ContentCaching()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/contentcaching');
        }
    }

    public function testSSLTermination()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->SSLTermination()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                                        'loadbalancers/123/ssltermination');
        }
    }

    public function testMetadata()
    {
        $lb = $this->service->LoadBalancer();
        $lb->Create();
        $urls = $lb->Metadata()->Url();
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/' .
                    'loadbalancers/123/metadata');
        }
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
        
        $this->assertEquals('OpenCloud\Tests\LoadBalancer\MySubResource', get_class($sub));
        
        $urls = $sub->Url('foo', array('one' => 1));
        foreach($urls as $url) {
            $this->assertEquals($url, 'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/TENANT-ID/loadbalancers/42/ignore');
        }
        
        $obj = $sub->UpdateJson();
        $json = json_encode($obj);
        
        $this->assertEquals('{"ignore":{"id":"42"}}', $json);
        
        $this->assertEquals($this->loadBalancer, $sub->getParent());
        
        $this->assertEquals('OpenCloud\Tests\LoadBalancer\MySubResource-42', $sub->Name());
    }

    public function testUpdate()
    {

        $lb = $this->service->LoadBalancer();
        $lb->Create();

        $resp = $lb->Update(array(
            'algorithm' => 'ROUND_ROBIN',
            'protocol' => 'HTTP',
            'port' => '8080'
        ));

        $this->assertNotNull($resp->HttpStatus());

        $this->assertEquals('ROUND_ROBIN',$lb->algorithm);
        $this->assertEquals('HTTP',$lb->protocol);
        $this->assertEquals('8080',$lb->port);
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
