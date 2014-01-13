<?php
/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
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