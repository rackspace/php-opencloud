<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Queues\Resource;

use OpenCloud\Queues\Resource\Message;

class MessageTest extends \OpenCloud\Tests\OpenCloudTestCase 
{
    private $service;
    private $queue;
    private $message;
    
    public function __construct()
    {
        $this->service = $this->getClient()->queuesService('cloudQueues', 'ORD');
        $this->queue = $this->service->getQueue()->setName('foo');
        $this->message = $this->queue->listMessages()->first();   
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
        
        $this->message->getHref();
        $this->message->getAge();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Creating_Message()
    {
        $message = new Message();
        $message->setService($this->service)->setParent($this->queue);
        $message->setTtl(100)->setBody('foo')->create();  
    }
    
    public function test_Batch_Create()
    {
        $this->queue->createMessages(array(
            array('body' => 'Do homework', 'ttl' => 3600)
        ));
        
        $response = $this->queue->setName('test-queue')->createMessages(array(
            array('body' => 'Do homework', 'ttl' => 3600)
        ));
        
        $this->assertTrue($response);
    }
    
    /**
     * @expectedException OpenCloud\Common\Http\Exception\UnexpectedResponseException
     */
    public function test_Batch_Create_Fails_When_Queue_Not_Found()
    {
        $queue = $this->queue->setName('foobar');
        $queue->createMessages(array(
            array('body' => 'Do homework', 'ttl' => 3600)
        ));
    }
    
    public function test_Collection()
    {
        $messages = $this->queue->listMessages();
        while ($message = $messages->next()) {
            $this->assertInstanceOf('OpenCloud\Queues\Resource\Message', $message);
            break;
        }
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
        
}