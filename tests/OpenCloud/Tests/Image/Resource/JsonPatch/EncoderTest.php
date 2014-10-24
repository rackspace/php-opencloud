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

namespace OpenCloud\Tests\Image\Resource\JsonPatch;

use OpenCloud\Image\Enum\OperationType;
use OpenCloud\Image\Resource\JsonPatch\Encoder;
use OpenCloud\Image\Resource\JsonPatch\Operation;
use OpenCloud\Image\Resource\Schema\Property;
use OpenCloud\Tests\OpenCloudTestCase;

class EncoderTest extends OpenCloudTestCase
{
    public function test_Escaping_Slash()
    {
        $operation = new Operation();
        $operation->setPath('/foo');
        $operation->setType(OperationType::REPLACE);
        $operation->setValue('/bar/baz');

        $encoded = Encoder::encode(array($operation));

        $this->assertEquals('[{"op": "replace", "path": "/foo", "value": "~1bar~1baz"}]', $encoded);
    }

    public function test_Escaping_Tilde()
    {
        $property = new Property();
        $property->setName('~foo');

        $operation = new Operation();
        $operation->setPath($property->getPath());
        $operation->setType(OperationType::REPLACE);
        $operation->setValue('bar~baz~');

        $encoded = Encoder::encode(array($operation));

        $this->assertEquals('[{"op": "replace", "path": "/~0foo", "value": "bar~0baz~0"}]', $encoded);
    }

    public function test_Transform()
    {
        $this->assertEquals('~0', Encoder::transform('~'));
        $this->assertEquals('~1', Encoder::transform('/'));

        $this->assertEquals('A', Encoder::transform('A'));
        $this->assertEquals('!!!!', Encoder::transform('!!!!'));
    }
}
