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

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Queues\Resource\Queue;

class Queues extends AbstractUnit implements UnitInterface
{
    const QUEUE_NAME = 'test_queue';

    private $queue;

    public function setupService()
    {
        $service = $this->getConnection()->queuesService('cloudQueues');
        $service->setClientId($service::generateUuid());
        return $service;
    }

    public function main()
    {
        $this->doQueueBlock();
        $this->doMessageBlock();
    }

    public function doQueueBlock()
    {
        $this->step('Queues');

        // create
        $this->stepInfo('Create queue');
        $this->queue = $this->getService()->createQueue(self::QUEUE_NAME . rand(1, 9999));

        // check existence
        $this->stepInfo('Check queue existence');
        $this->getService()->hasQueue($this->prepend(self::QUEUE_NAME));

        // metadata
        $this->stepInfo('Update metadata');
        $this->queue->saveMetadata(array(
            'foo' => 'bar'
        ));

        // stats
        $stats = $this->queue->getStats();
        $this->stepInfo('Get stats: %s', print_r($stats, true));

        // list
        $step = $this->stepInfo('List queues');
        $queues = $this->getService()->listQueues();
        foreach ($queues as $queue) {
            $step->stepInfo($queue->getName());
        }
    }

    public function doMessageBlock()
    {
        $this->step('Messages');

        // post
        $numMessagesCreated = 0;
        $this->stepInfo('Create messages for queue %s', $this->queue->getName());
        $this->queue->createMessage(array(
            'body' => (object) array(
                    'instructions' => 'Do it now!'
                ),
            'ttl' => 300
        ));
        ++$numMessagesCreated;

        for ($creationBatch = 0; $creationBatch < 3; ++$creationBatch) {
            $messages = array();
            for ($messageIndex = 0; $messageIndex < Queue::MAX_POST_MESSAGES; ++$messageIndex) {
                $messages[] = array(
                    'body' => (object) array('message_number' => ($creationBatch * Queue::MAX_POST_MESSAGES) + $messageIndex + 1),
                    'ttl'  => mt_rand(300, 600)
                );
                ++$numMessagesCreated;
            }
            $this->queue->createMessages($messages);
        }

        // list ( 'echo' => true is needed to list client's own messages)
        $step = $this->stepInfo('List messages for queue %s', $this->queue->getName());
        $messages = $this->queue->listMessages(array(
            'echo' => true
        ));
        $ids = array();
        $numMessagesListed = 0;
        $step->stepInfo("%-30s | %-40s", "Message ID", "Message body");
        $step->stepInfo("%-30s | %-40s", str_repeat("-", 30), str_repeat("-", 40));
        foreach ($messages as $message) {
            $step->stepInfo("%-30s | %-40s", $message->getId(), json_encode($message->getBody()));
            $ids[] = $message->getId();
            ++$numMessagesListed;
        }
        $this->stepInfo("Number of messages to be listed:    " . $numMessagesCreated);
        $this->stepInfo("Number of messages actually listed: " . $numMessagesListed);
        $this->assertEquals($numMessagesCreated, $numMessagesListed);

        array_pop($ids);
    }

    public function doClaimBlock()
    {
        $this->step('Claims');

        // claim
        $this->stepInfo('Create claims');

        $this->queue->claimMessages(array(
            'ttl'   => 300,
            'grace' => 300,
            'limit' => 15
        ));
    }

    public function teardown()
    {
        $this->step('Delete queues');

        $queues = $this->getService()->listQueues();
        foreach ($queues as $queue) {
            if ($this->shouldDelete($queue->getName())) {
                try {
                    $this->stepInfo('Deleting %s', $queue->getName());
                } catch (\Exception $e) {
                    $this->stepInfo('Failed to delete %s', $queue->getName());
                }
            }
        }
    }
}
