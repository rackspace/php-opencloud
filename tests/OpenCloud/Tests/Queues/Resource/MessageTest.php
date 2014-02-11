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

namespace OpenCloud\Tests\Queues\Resource;

use OpenCloud\Tests\Queues\QueuesTestCase;

class MessageTest extends QueuesTestCase
{
    private $message;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->getTestFilePath('Message'));
        $this->message = $this->queue->getMessage('foo');
    }

    public function test_SettingTtl()
    {
        $this->message->setBody('FOO BAR');

        $this->message->setTtl(100);
        $this->assertEquals(100, $this->message->getTtl());

        $this->message->setId('foo');
        $this->message->setBody('bar');

        $this->assertEquals('foo', $this->message->getId());
        $this->assertEquals('bar', $this->message->getBody());

        $this->assertNotEmpty($this->message->getHref());
        $this->assertNotEmpty($this->message->getAge());
        $this->assertNull($this->message->getClaimIdFromHref());
    }

    public function test_Batch_Create()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));

        $response = $this->queue->createMessages(array(
            array('body' => 'Do homework', 'ttl' => 3600)
        ));
        $this->assertTrue($response);

        $this->addMockSubscriber($this->makeResponse(null, 201));

        $response = $this->queue->setName('test-queue')->createMessages(array(
            array('body' => 'Do homework', 'ttl' => 3600)
        ));
        $this->assertTrue($response);
    }

    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function test_Batch_Create_Fails_When_Queue_Not_Found()
    {
        $this->addMockSubscriber($this->makeResponse(null, 404));

        $this->queue->createMessages(array(
            array('body' => 'Do homework', 'ttl' => 3600)
        ));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Update_Fails()
    {
        $this->message->update();
    }

    public function test_Delete()
    {
        $this->message->setId('foo');
        $this->message->delete();
    }

    public function test_Extract_ClaimId()
    {
        $this->message->setHref("/foo/bar/baz?claim_id=123456789");
        $this->assertEquals("123456789", $this->message->getClaimIdFromHref());
    }
}
