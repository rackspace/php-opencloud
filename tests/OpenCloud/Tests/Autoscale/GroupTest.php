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

        $this->service = new Service($connection, 'autoscale', 'DFW', 'publicURL', self::ENDPOINT);
        
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
    
}