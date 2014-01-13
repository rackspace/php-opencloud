<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\Autoscale\AutoscaleTestCase;

class ScalingPolicyTest extends AutoscaleTestCase
{

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->getTestFilePath('Policy'));
        $this->policy = $this->group->getScalingPolicy(self::POLICY_ID);
    }

    /**
     * @mockFile Policy_List
     */
    public function test_Collection_Class()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->group->getScalingPolicies()
        );
    }

    /**
     * @mockFile Webhook
     */
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
        $this->addMockSubscriber(new Response(200));
        $response = $this->policy->execute()->getStatusCode();
        $this->assertTrue($response >= 200 && $response < 300);
    }

    public function test_Webhook_Create()
    {
        $this->addMockSubscriber(new Response(201));
        $this->policy->getWebhook()->create(array('name' => 'New hook'));
    }
}