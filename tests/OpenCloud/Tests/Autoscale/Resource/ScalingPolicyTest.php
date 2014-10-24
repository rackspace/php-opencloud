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

    public function testCreatingWebhooks()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));

        $response = $this->policy->createWebhooks(array(
            (object) array(
                'name'     => 'My webhook',
                'metadata' => array(
                    'firstKey'  => 'foo',
                    'secondKey' => 'bar'
                )
            )
        ));

        $this->isResponse($response);
    }
}
