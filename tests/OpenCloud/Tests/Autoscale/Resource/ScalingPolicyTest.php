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
use OpenCloud\Autoscale\Resource\ScalingPolicy;
use OpenCloud\Autoscale\Service;

class ScalingPolicyTest extends PHPUnit_Framework_TestCase 
{

    const ENDPOINT  = 'https://private-f52bc-autoscale.apiary.io/v1.0/tenantId/';
    const GROUP_ID  = '{groupId}';
    const POLICY_ID = '{policyId}';
    
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection';
    const POLICY_CLASS     = 'OpenCloud\Autoscale\Resource\ScalingPolicy';
    const GROUP_CLASS      = 'OpenCloud\Autoscale\Resource\Group';
    
    private $service;
    private $group;
    
    public function __construct()
    {
        $connection = new FakeConnection(
            'http://example.com', 
            'SECRET'
        );

        $this->service = new Service($connection, 'autoscale', 'DFW', 'publicURL', self::ENDPOINT); 
        $this->group   = $this->service->group(self::GROUP_ID);
    }
    
    public function testCollection()
    {
        $policies = $this->group->getPolicies();
        
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $policies
        );
        
        $first = $policies->first();
        
        $this->assertEquals('{policyId1}', $first->id);
        $this->assertEquals('webhook', $first->type);
    }
    
    public function testIndividual()
    {
        $policy = $this->group->getPolicy(self::POLICY_ID);
       
        $this->assertInstanceOf(self::POLICY_CLASS, $policy);
        $this->assertEquals(1, $policy->change);
    }
    
}