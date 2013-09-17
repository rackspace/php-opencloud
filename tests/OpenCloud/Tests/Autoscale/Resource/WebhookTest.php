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
use OpenCloud\Autoscale\Resource\Webhook;
use OpenCloud\Autoscale\Service;

class WebhookTest extends PHPUnit_Framework_TestCase 
{
    
    const ENDPOINT   = 'https://private-f52bc-autoscale.apiary.io/v1.0/tenantId/';
    const GROUP_ID   = '{groupId}';
    const POLICY_ID  = '{policyId}';
    const WEBHOOK_ID = '{webhookId}';
    
    const COLLECTION_CLASS = 'OpenCloud\Common\Collection';
    const WEBHOOK_CLASS    = 'OpenCloud\Autoscale\Resource\Webhook';
    const GROUP_CLASS      = 'OpenCloud\Autoscale\Resource\Group';
    
    private $policy;
    
    public function __construct()
    {
        $connection = new FakeConnection(
            'http://example.com', 
            'SECRET'
        );

        $service = new Service($connection, 'autoscale', array('DFW'), 'publicURL', array(self::ENDPOINT)); 
        $group   = $service->group(self::GROUP_ID);
        
        $this->policy = $group->getPolicy(self::POLICY_ID);
    }

    public function testWebhookClass()
    {
        $webhook = $this->policy->getWebhook(self::WEBHOOK_ID);
        $this->assertInstanceOf(self::WEBHOOK_CLASS, $webhook);
        
        $webhooks = $this->policy->getWebhookList();
        $this->assertInstanceOf(self::COLLECTION_CLASS, $webhooks);
        
        $first = $webhooks->first();
        $this->assertInstanceOf(self::WEBHOOK_CLASS, $first);
        $this->assertEquals('alice', $first->name);
    }
    
    public function testCreate()
    {
        $response = $this->policy->getWebhook()->create(array(
           'name'     => 'alice',
           'metadata' => array(
               'notes' => 'foobar'
           )
        ));
        
        $this->assertNotNull($response);
    }
    
}