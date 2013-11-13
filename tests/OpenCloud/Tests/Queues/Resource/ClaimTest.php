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

class ClaimTest extends \OpenCloud\Tests\OpenCloudTestCase 
{
    private $service;
    private $queue;
    private $message;
    
    public function __construct()
    {
        $this->service = $this->getClient()->queuesService('cloudQueues', 'ORD', 'internalURL');
        $this->queue = $this->service->getQueue()->setName('foo');
        $this->claim = $this->queue->getClaim('foo');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Create_Fails()
    {
        $this->queue->getClaim()->create();
    }
    
    public function test_Getting_Claim()
    {
        $claim = $this->queue->getClaim('fooz');
        $this->assertNotNull($claim->getId());
        $this->assertNotNull($claim->getTtl());
        $this->assertNotNull($claim->getHref());
    }
    
    public function test_Update()
    {
        $this->claim->update(array(
            'grace' => 10,
            'ttl'   => 100
        ));
        
        $this->claim->getAge();
        $this->claim->getId();
        $this->claim->getMessages();
    }
    
}