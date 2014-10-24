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

namespace OpenCloud\Tests\ObjectStore\Resource;

use Guzzle\Http\EntityBody;
use OpenCloud\ObjectStore\Upload\TransferBuilder;
use OpenCloud\Tests\ObjectStore\ObjectStoreTestCase;

class TransferTest extends ObjectStoreTestCase
{
    public function test_Consecutive_Transfer()
    {
        $options = array('objectName' => 'NEW_OBJECT');

        $transfer = TransferBuilder::newInstance()
            ->setOptions($options)
            ->setEntityBody(EntityBody::factory(str_repeat('A', 100)))
            ->setContainer($this->container)
            ->build();

        $this->assertCount(7, $transfer->getOptions());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Consecutive_Transfer_Fails_Without_Object_Name()
    {
        TransferBuilder::newInstance()
            ->setOptions(array('objectName' => false))
            ->setEntityBody(EntityBody::factory(str_repeat('A', 100)))
            ->setContainer($this->container)
            ->build();
    }
}
