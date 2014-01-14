<?php
/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
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