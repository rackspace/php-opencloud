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

namespace OpenCloud\Tests\LoadBalancer\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\LoadBalancer\LoadBalancerTestCase;

class LoadBalancerTest extends LoadBalancerTestCase
{
    public function test_Add_Node()
    {
        $lb = $this->service->LoadBalancer();
        $lb->addNode('1.1.1.1', 80);

        $this->assertEquals('1.1.1.1', $lb->nodes[0]->address);
    }

    public function test_Remove_Node()
    {
        $lb = $this->service->LoadBalancer();

        $lb->addNode('1.1.1.1', 80);
        $lb->addNodes();

        $lb->removeNode(1040);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\MissingValueError
     */
    public function test_Adding_Nodes_Fails_When_Empty()
    {
        $this->service->loadBalancer()->addNodes();
    }

    public function testAddVirtualIp()
    {
        $lb = $this->service->loadBalancer();
        $lb->addVirtualIp('public');

        $this->assertEquals('PUBLIC', $lb->virtualIps[0]->type);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DomainError
     */
    public function test_Add_VIP_Fails_With_Incorrect_Type_Arg()
    {
        $this->loadBalancer->addVirtualIp(1, 2, 'foo');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DomainError
     */
    public function test_Add_VIP_Fails_With_Incorrect_Condition_Arg()
    {
        $this->loadBalancer->addVirtualIp(1, 2, 'ENABLED', 'foo');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DomainError
     */
    public function test_Add_VIP_Fails_With_Incorrect_Weight_Arg()
    {
        $this->loadBalancer->addVirtualIp(1, 2, 'ENABLED', 'PRIMARY', 'foo');
    }

    public function testNode()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/nodes/321',
            (string)$this->loadBalancer->node('321')->getUrl()
        );

        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\LoadBalancer',
            $this->loadBalancer->node('345')->getParent()
        );

        $this->assertEquals(
            'OpenCloud\LoadBalancer\Resource\Node[456]',
            (string)$this->loadBalancer->node('456')->name()
        );

        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Resource\Metadata',
            $this->loadBalancer->node('456')->metadata()
        );

        $this->isCollection($this->loadBalancer->node('456')->metadataList());

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
        $this->isCollection($lb->nodeList());
    }

    public function testNodeEvent()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/nodes/events',
            (string) $this->loadBalancer->nodeEvent()->getUrl()
        );
    }

    public function testNodeEventList()
    {
        $this->isCollection($this->loadBalancer->nodeEventList());
    }

    public function testVirtualIp()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/virtualips',
            (string) $this->loadBalancer->virtualIp()->getUrl()
        );
    }

    public function testVirtualIpList()
    {
        $this->isCollection($this->loadBalancer->virtualIpList());
    }

    public function testSessionPersistence()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/sessionpersistence',
            (string) $this->loadBalancer->sessionPersistence()->getUrl()
        );
    }

    public function testErrorPage()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/errorpage',
            (string) $this->loadBalancer->errorPage()->getUrl()
        );
    }

    public function testHealthMonitor()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/healthmonitor',
            (string) $this->loadBalancer->healthMonitor()->getUrl()
        );
    }

    public function testStats()
    {
        $this->assertInstanceOf('OpenCloud\LoadBalancer\Resource\Stats', $this->loadBalancer->stats());
    }

    public function testUsage()
    {
        $this->isCollection($this->loadBalancer->usage());
    }

    public function testAccess()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/accesslist',
            (string) $this->loadBalancer->access()->getUrl()
        );
    }

    public function testAccessList()
    {
        $loadBalancer = $this->loadBalancer;

        $this->addMockSubscriber($this->makeResponse('{"accessList":[{"address":"206.160.163.21","id":23,"type":"DENY"}]}'));

        $list = $loadBalancer->accessList();
        $this->isCollection($list);
        $this->assertEquals(1, $list->count());
        $this->assertEquals('206.160.163.21', $list->first()->getAddress());
    }

    public function test_Connection_Throttle()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/connectionthrottle',
            (string) $this->loadBalancer->connectionThrottle()->getUrl()
        );
    }

    public function test_Has_Connection_Logging()
    {
        $this->addMockSubscriber($this->makeResponse('{"connectionLogging": {"enabled": true}}'));
        $this->assertTrue($this->loadBalancer->hasConnectionLogging());

        $this->addMockSubscriber($this->makeResponse('{"connectionLogging": {"enabled": false}}'));
        $this->assertFalse($this->loadBalancer->hasConnectionLogging());
    }

    public function test_Setting_Connection_Logging_Returns_Response()
    {
        $this->isResponse($this->loadBalancer->enableConnectionLogging(true));
    }

    public function test_Has_Content_Caching()
    {
        $this->addMockSubscriber($this->makeResponse('{"contentCaching": {"enabled": true}}'));
        $this->assertTrue($this->loadBalancer->hasContentCaching());

        $this->addMockSubscriber($this->makeResponse('{"contentCaching": {"enabled": false}}'));
        $this->assertFalse($this->loadBalancer->hasContentCaching());
    }

    public function test_Setting_Content_Caching_Returns_Response()
    {
        $this->isResponse($this->loadBalancer->enableContentCaching(true));
    }

    public function test_SSL_Termination()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/ssltermination',
            (string)$this->loadBalancer->SSLTermination()->Url()
        );
    }

    public function test_Certificate_Mapping()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/ssltermination/certificatemappings',
            (string)$this->loadBalancer->certificateMapping()->Url()
        );
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/ssltermination/certificatemappings/1',
            (string)$this->loadBalancer->certificateMapping(1)->Url()
        );
    }

    public function test_Metadata()
    {
        $this->assertEquals(
            'https://dfw.loadbalancers.api.rackspacecloud.com/v1.0/123456/loadbalancers/2000/metadata',
            (string)$this->loadBalancer->Metadata()->Url()
        );
    }

    public function test_Metadata_List()
    {
        $this->isCollection($this->loadBalancer->metadataList());
    }

    public function test_Update()
    {
        $lb = $this->service->loadBalancer();

        $lb->update(array(
            'algorithm' => 'ROUND_ROBIN',
            'protocol'  => 'HTTP',
            'port'      => '8080'
        ));

        $this->assertEquals('ROUND_ROBIN', $lb->algorithm);
        $this->assertEquals('HTTP', $lb->protocol);
        $this->assertEquals('8080', $lb->port);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Update_Fails_Without_Correct_Fields()
    {
        $this->loadBalancer->update(array('foo' => 'bar'));
    }

    public function test_Adding_Node_With_Type()
    {
        $this->loadBalancer->addNode('localhost', 8080, 'ENABLED', 'PRIMARY', 10);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_Adding_Node_Fails_Without_Correct_Type()
    {
        $this->loadBalancer->addNode('localhost', 8080, 'ENABLED', 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_Adding_Node_Fails_Without_Correct_Weight()
    {
        $this->loadBalancer->addNode('localhost', 8080, 'ENABLED', 'PRIMARY', 'baz');
    }

    public function test_Adding_VIP()
    {
        $this->loadBalancer->id = 2000;
        $this->loadBalancer->addVirtualIp(123, 4);
        $this->loadBalancer->addVirtualIp('PUBLIC', 6);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DomainError
     */
    public function test_Adding_VIP_Fails_With_Incorrect_IP_Type()
    {
        $this->loadBalancer->addVirtualIp(123, 5);
    }

    public function test_Creating_Access_List()
    {
        $this->addMockSubscriber($this->makeResponse());

        $response = $this->loadBalancer->createAccessList(array(
            (object) array(
                'type'    => 'ALLOW',
                'address' => '206.160.165.1/24'
            ),
            (object) array(
                'type'    => 'DENY',
                'address' => '0.0.0.0/0'
            )
        ));

        $this->isResponse($response);
    }

    public function test_Health_Monitor_Is_Refreshed_With_Bespoke_Method()
    {
        $body = '{"healthMonitor":{"type": "CONNECT","delay": 10,"timeout": 10,"attemptsBeforeDeactivation": 3}}';
        $this->addMockSubscriber($this->makeResponse($body, 200));

        $health = $this->loadBalancer->healthMonitor();
        $health->refresh();

        $this->assertEquals($health->type, 'CONNECT');
    }

    public function testAddNodeToExistingLoadBalancer()
    {
        $lb = $this->loadBalancer;

        $lb->addNode('2.2.2.2', 80);
        $lb->addNodes();
    }
}
