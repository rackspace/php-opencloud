<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale;

class ScalingPolicyTest extends \OpenCloud\Tests\OpenCloudTestCase 
{
    
    const GROUP_ID = 'groupId';
    const POLICY_ID = 'policyId';
    const WEBHOOK_ID = 'webhookId';
    
    private $group;
    
    public function __construct()
    {
        $this->service = $this->getClient()->autoscale('autoscale', 'DFW', 'publicURL'); 
        $this->policy = $this->service->group(self::GROUP_ID)->getScalingPolicy(self::POLICY_ID);
    }
    
    public function test_Get_Webhook_List()
    {
        $list = $this->policy->getWebhookList();
        while ($webhook = $list->next()) {
            $this->assertInstanceOf(
                'OpenCloud\Autoscale\Resource\Webhook',
                $webhook
            );
            break;
        }
    }
    
    public function test_Get_Webhook()
    {
        $webhook = $this->policy->getWebhook(self::WEBHOOK_ID);
        $this->assertInstanceOf(
            'OpenCloud\Autoscale\Resource\Webhook',
            $webhook
        );
        
        $this->assertEquals(self::WEBHOOK_ID, $webhook->id);
    }
    
    public function test_Execute()
    {
        $response = $this->policy->execute()->getStatusCode();
        $this->assertTrue($response >= 200 && $response < 300);
    }
    
    public function test_Webhook_Create()
    {
        $this->policy->getWebhook()->create(array('name' => 'New hook'));
    }
}