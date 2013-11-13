<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Queues\Resource\Resource;

use OpenCloud\Queues\Service;
use OpenCloud\Common\Metadata;

/**
 * Description of QueueTest
 * 
 * @link 
 */
class QueueTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    
    private $service;
    private $queue;
    
    public function __construct()
    {
        $this->service = $this->getClient()->queuesService('cloudQueues', 'ORD');
        $this->queue = $this->service->getQueue();
    }
        
    public function test_Create()
    { 
        $this->service->createQueue('test');
    }
    
    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function test_Create_Fails_With_Incorrect_Response()
    {
        $this->service->createQueue('baz');
    }
    
    /**
     * @expectedException OpenCloud\Queues\Exception\QueueException
     */
    public function test_Create_Fails_With_Incorrect_Name()
    {
        $this->service->createQueue('baz!!!*');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Update_Fails()
    {
        $this->queue->update(array('name' => 'new name'));
    }
    
    public function test_Delete()
    {
        $this->queue->setName('test');
        $this->queue->delete();
    }
    
    public function test_Metadata()
    {
        $this->queue->setName('test')
                    ->saveMetadata(array(
                        'new metadata' => 'bar'
                    ));
        
        $metadata = $this->queue->getMetadata();

        $this->assertInstanceOf('OpenCloud\Common\Metadata', $metadata);
        $this->assertEquals('bar', $metadata->{'new metadata'});
        
        $newMetadata = new Metadata();
        $newMetadata->setArray(array(
            'foo' => 'bar'
        ));
        $this->queue->setMetadata($newMetadata);
    }
    
    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function test_Metadata_Fails_If_Queue_Not_Found()
    {
        $this->service->getQueue('foobar')->retrieveMetadata();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_SetMetadata_Fails_Without_Correct_Data()
    {
        $this->queue->setMetadata('');
    }
            
    public function test_Stats()
    {
        $this->assertNotNull($this->queue->setName('foo')->getStats());
    }
    
    public function test_Get_Message()
    {
        $this->assertInstanceOf(
            'OpenCloud\Queues\Resource\Message', 
            $this->queue->getMessage()
        );
    }
    
    public function test_List_Message()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $this->queue->setName('foo')->listMessages(array(
                'ids' => array(100, 901, 58)
            ))
        );
    }
    
    public function test_Delete_Message()
    {
        $this->assertTrue(
            $this->queue->setName('foo')->deleteMessages(array(100, 901, 58))
        );
    }
            
    public function test_Claim_Messages()
    {
        $this->assertInstanceOf(
            'OpenCloud\Queues\Resource\Message',
            $this->queue->setName('foo')->claimMessages()->first()
        );
        $this->assertFalse($this->queue->setName('foobar')->claimMessages());
    }
    
    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function test_Claim_Messages_Fails_If_Queue_Not_Found()
    {
        $queue = $this->queue->setName('baz');
        $queue->claimMessages();
    }
    
    public function test_Getting_Claim()
    {
        $claim = $this->queue->getClaim();
        
        $this->assertInstanceOf('OpenCloud\Queues\Resource\Claim', $claim);
        $this->assertEquals($this->queue, $claim->getParent());
    }
    
}