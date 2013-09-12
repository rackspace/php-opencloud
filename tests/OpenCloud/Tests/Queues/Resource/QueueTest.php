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
use OpenCloud\Queues\Service;
use OpenCloud\Tests\StubConnection;
use OpenCloud\Common\Metadata;

/**
 * Description of QueueTest
 * 
 * @link 
 */
class QueueTest extends PHPUnit_Framework_TestCase
{
    
    private $connection;
    private $service;
    private $queue;
    
    public function __construct()
    {
        $this->connection = new StubConnection('foo', 'bar');
        $this->service = new Service($this->connection, 'cloudQueues', 'ORD');
        $this->queue = $this->service->getQueue();
    }
    
    public function test_Create()
    { 
        $this->queue->create(array(
            'name'     => 'test',
            'metadata' => array(
                'foo' => 'bar'
            )
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Create_Fails_With_Incorrect_Name()
    {
        $this->queue->create(array(
            'name' => 'AWESOME!!!'
        ));
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
                    ->setMetadata(array(
                        'new metadata' => 'bar'
                    ), true);
        
        $metadata = $this->queue->getMetadata();

        $this->assertInstanceOf('OpenCloud\Common\Metadata', $metadata);
        $this->assertEquals('Omega', $metadata->{'new metadata'});
        
        $newMetadata = new Metadata();
        $newMetadata->setArray(array(
            'foo' => 'bar'
        ));
        $this->queue->setMetadata($newMetadata);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_SetMetadata_Fails_Without_Correct_Data()
    {
        $this->queue->setMetadata('');
    }
    
    /**
     * @expectedException OpenCloud\Queues\Exception\QueueMetadataException
     */
    public function test_SetMetadata_Fails_Without_Response()
    {
        $this->queue->setName('Awesome!!')->setMetadata(array(), true);
    }
    
    /**
     * @expectedException OpenCloud\Queues\Exception\QueueMetadataException
     */
    public function test_GetMetadata_Fails_Without_Response()
    {
        $this->queue->setName('Awesome!!')->getMetadata();
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
    
    /**
     * @expectedException OpenCloud\Queues\Exception\DeleteMessageException
     */
    public function test_Delete_Message_Fails_Without_Correct_Response()
    {
        $this->assertTrue(
            $this->queue->setName('foo!!')->deleteMessages(array(100))
        );
    }
    
    public function test_Claim_Messages()
    {
        $this->assertTrue($this->queue->setName('foo')->claimMessages());
        $this->assertFalse($this->queue->setName('foobar')->claimMessages());
    }
    
    /**
     * @expectedException OpenCloud\Queues\Exception\MessageException
     */
    public function test_Claim_Messages_Fails_Without_Correct_Response()
    {
        $this->assertTrue($this->queue->setName('foo!!!')->claimMessages());
    }
    
    public function test_Getting_Claim()
    {
        $claim = $this->queue->getClaim();
        
        $this->assertInstanceOf('OpenCloud\Queues\Resource\Claim', $claim);
        $this->assertEquals($this->queue, $claim->getParent());
    }
    
}