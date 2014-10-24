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

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version   1.0.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\Metadata;

class MetadataTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $metadata;

    public function __construct()
    {
        $this->metadata = new Metadata;
    }

    public function test__set()
    {
        $this->metadata->foo = 'bar';
        $this->assertEquals('bar', $this->metadata->foo);

        $this->assertTrue(isset($this->metadata->foo));
    }

    public function testSetArray()
    {
        $array = array('opt' => 'uno', 'foobar' => 'baz');
        $this->metadata->setArray($array);
        $this->assertEquals('uno', $this->metadata->opt);
        $this->assertEquals($array, $this->metadata->keylist());
        $this->metadata->setArray(array('X-one' => 1, 'X-two' => 2), 'X-');
        $this->assertEquals(2, $this->metadata->two);
    }
}
