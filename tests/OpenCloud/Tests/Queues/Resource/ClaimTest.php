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
