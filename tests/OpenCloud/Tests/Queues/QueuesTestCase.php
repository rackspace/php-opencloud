<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Queues;

use OpenCloud\Tests\OpenCloudTestCase;

class QueuesTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $queue;

    protected $mockPath = 'Queues';

    public function setupObjects()
    {
        $this->service = $this->getClient()->queuesService('cloudQueues', 'ORD');

        $this->queue = $this->service->getQueue();
        $this->queue->setName('foo');
    }
}