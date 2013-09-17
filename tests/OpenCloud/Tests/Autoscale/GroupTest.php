<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale;

use PHPUnit_Framework_TestCase;
use OpenCloud\Autoscale\Service;

class GroupTest extends PHPUnit_Framework_TestCase 
{
    
    const ENDPOINT = 'https://private-f52bc-autoscale.apiary.io/v1.0/tenantId/';
    const GROUP_ID = '{groupId}';
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection';
    
    private $service;
    
    public function __construct()
    {
        $connection = new FakeConnection(
            'http://example.com', 
            'SECRET'
        );

        $this->service = new Service($connection, 'autoscale', 'DFW', 'publicURL', array(self::ENDPOINT));
        
    }
    
    public function testGroup()
    {
        $group = $this->service->group();
        $group->refresh(self::GROUP_ID);
        
        // Test class
        $this->assertInstanceOf('OpenCloud\Autoscale\Resource\Group', $group);
        
        // Test basic property
        $this->assertEquals($group->id, self::GROUP_ID);
        
        // Test resource association
        $this->assertInstanceOf(
            'OpenCloud\Autoscale\Resource\GroupConfiguration', 
            $group->groupConfiguration
        );
        $this->assertEquals('workers', $group->groupConfiguration->name);
        
        // Test collection association
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $group->scalingPolicies
        );
        
        // Test individual resources in collection
        $first = $group->scalingPolicies->First();
        $this->assertEquals(150, $first->cooldown);
        $this->assertInstanceOf('OpenCloud\Autoscale\Resource\ScalingPolicy', $first);
    }
    
    public function testGroups()
    {
        $groups = $this->service->groupList();
        
        // Test class
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $groups
        );
        
        $first = $groups->first();
        $this->assertEquals('{groupId1}', $first->id);
        
        $second = $groups->next();
        $this->assertFalse($second->paused);
    }
    
    public function testGroupState()
    {
        $group = $this->service->group(self::GROUP_ID);
        
        $state = $group->getState();
        
        $this->assertEquals(2, $state->activeCapacity);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\JsonError
     */
    public function testCreateFailsWithIncorrectJsonString()
    {
        $this->service->group()->create('somethingIncorrect');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function testCreateFailsWithIncorrectParams()
    {
        $this->service->group()->create(false);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateAlwaysFails()
    {
        $this->service->group(self::GROUP_ID)->update();
    }
    
    public function testCreateAndUpdate()
    {
        $json = <<<EOT
{"metadata":{"foo":"bar"},"groupConfiguration":{"name":"workers","cooldown":60,"minEntities":5,"maxEntities":25,"metadata":{"firstkey":"this is a string","secondkey":"1"}},"launchConfiguration":{"type":"launch_server","args":{"server":{"flavorRef":3,"name":"webhead","imageRef":"0d589460-f177-4b0f-81c1-8ab8903ac7d8","OS-DCF:diskConfig":"AUTO","metadata":{"mykey":"myvalue"},"personality":[{"path":"/root/.ssh/authorized_keys","contents":"ssh-rsa AAAAB3Nza...LiPk== user@example.net"}],"networks":[{"uuid":"11111111-1111-1111-1111-111111111111"}]},"loadBalancers":[{"loadBalancerId":2200,"port":8081}]}},"scalingPolicies":[{"name":"scale up by 10","change":10,"cooldown":5,"type":"webhook"},{"name":"scale down by 5.5 percent","changePercent":-5.5,"cooldown":6,"type":"webhook"},{"name":"set number of servers to 10","desiredCapacity":10,"cooldown":3,"type":"webhook"}]}
EOT;
       
        $response = $this->service->group()->create($json);
        
        $this->assertNotNull($response);
        
        $config = $this->service->group(self::GROUP_ID)->getGroupConfig();
        $config->update(array(
            'name' => 'new_name',
            'metadata' => array(
                'foo' => 1,
                'bar' => 2
            )
        ));
    }
    
}