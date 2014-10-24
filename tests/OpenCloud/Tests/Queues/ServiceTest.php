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

namespace OpenCloud\Tests\Queues;

class ServiceTest extends QueuesTestCase
{
    public function test_ClientId()
    {
        $rand = sha1(rand(1, 9999));
        $this->service->setClientId($rand);
        $this->assertEquals($rand, $this->service->getClientId());
    }

    /**
     * @mockFile Queue_List
     */
    public function test_List_Queues()
    {
        $queues = $this->service->listQueues(array('marker' => 2));
        $this->assertInstanceOf(self::COLLECTION_CLASS, $queues);

        $first = $queues->first();

        $this->assertEquals(
            '036b184b28fcb548349af623079119c6a966cbc',
            $first->getName()
        );

        $this->assertNotNull($first->getHref());
    }

    public function test_Get_Queue()
    {
        $queue = $this->service->getQueue();
        $this->assertInstanceOf('OpenCloud\Queues\Resource\Queue', $queue);
    }

    public function test_Has_Queue()
    {
        $this->addMockSubscriber($this->makeResponse(null, 204));
        $this->assertTrue($this->service->hasQueue('realQueue'));
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->assertFalse($this->service->hasQueue('foobar'));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Has_Queue_Fails_Without_Name()
    {
        $this->service->hasQueue(array());
    }
}
