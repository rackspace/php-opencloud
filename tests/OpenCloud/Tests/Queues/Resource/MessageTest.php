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

use PHPUnit_Framework_TestCase;
use OpenCloud\Queues\Resource\Message;
use OpenCloud\Queues\Service;
use OpenCloud\Tests\StubConnection;

class MessageTest extends PHPUnit_Framework_TestCase 
{
    private $connection;
    private $service;
    private $queue;
    private $message;
    
    public function __construct()
    {
        $this->connection = new StubConnection('foo', 'bar');
        $this->service = new Service($this->connection, 'cloudQueues', 'ORD');
        $this->queue = $this->service->getQueue()->setName('foo');
        $this->message = $this->queue->listMessages()->first();           
    }
    
    public function test_SettingTtl()
    {
        $this->message->setBody('FOO BAR');
        
        $this->message->setTtl(100);
        $this->assertEquals(100, $this->message->getTtl());
        
        $this->message->id = 'foo';
        $this->message->setBody('bar');
        
        $this->assertEquals('foo', $this->message->getId());
        $this->assertEquals('FOO BAR', $this->message->getBody());
        
        $this->message->getHref();
        $this->message->getAge();
    }
    
    public function test_Creating_Message()
    {
        $message = new Message();
        $message->setService($this->service)->setParent($this->queue);
        $message->setTtl(100)->setBody('foo')->create();  
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
        $this->message->id = 'foo';
        $this->message->delete();
    }
    
    /**
     * @expectedException OpenCloud\Queues\Exception\DeleteMessageException
     */
    public function test_Delete_Fails_Without_Correct_Response()
    {
        $this->message->id = 'foo!!';
        $this->message->delete();
    }
    
}