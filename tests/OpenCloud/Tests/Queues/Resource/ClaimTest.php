<?php
/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Queues\Resource\Resource;

use OpenCloud\Tests\Queues\QueuesTestCase;

class ClaimTest extends QueuesTestCase
{
    private $claim;
    
    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->getTestFilePath('Claim'));
        $this->claim = $this->queue->getClaim('foo');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Create_Fails()
    {
        $this->claim->create();
    }
    
    public function test_Getting_Claim()
    {
        $this->assertNotNull($this->claim->getId());
        $this->assertNotNull($this->claim->getTtl());
        $this->assertNotNull($this->claim->getHref());
    }
    
    public function test_Update()
    {
        $this->addMockSubscriber($this->makeResponse(null, 204));

        $this->claim->update(array(
            'grace' => 10,
            'ttl'   => 100
        ));
        
        $this->claim->getAge();
        $this->claim->getId();
        $this->claim->getMessages();
    }
    
}