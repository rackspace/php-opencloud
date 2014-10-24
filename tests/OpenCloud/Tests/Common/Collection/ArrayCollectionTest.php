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

namespace OpenCloud\Tests\Common\Collection;

use OpenCloud\Tests\OpenCloudTestCase;

class ArrayCollectionTest extends OpenCloudTestCase
{
    public function test_Basic_Operations()
    {
        $iterator = $this->getMockForAbstractClass('OpenCloud\Common\Collection\ArrayCollection');

        $iterator->setElements(array('foo', 'bar', 'baz', 'one', 'two'));

        $iterator->offsetSet(5, 'three');

        $this->assertEquals('three', $iterator->offsetGet(5));
        $this->assertEquals(6, $iterator->count());

        $iterator->append('four');
        $this->assertEquals(7, $iterator->count());

        $this->assertTrue(isset($iterator[6]));
        $this->assertFalse($iterator->offsetExists(600));

        $this->assertTrue($iterator->valueExists('one'));
        $this->assertFalse($iterator->valueExists('fifty'));

        unset($iterator[0]);
        $this->assertFalse($iterator->valueExists('foo'));
    }
}
