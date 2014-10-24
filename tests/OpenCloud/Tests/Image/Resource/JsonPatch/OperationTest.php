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
use OpenCloud\Image\Resource\JsonPatch\Operation;
use OpenCloud\Image\Resource\Schema\Property;
use OpenCloud\Image\Resource\Schema\Schema;
use OpenCloud\Tests\OpenCloudTestCase;

class OperationTest extends OpenCloudTestCase
{
    protected function getPropertyData()
    {
        return array(
            'name'        => 'id',
            "description" => "An identifier for the image",
            "pattern"     => "^([0-9a-fA-F]){8}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){12}$",
            "type"        => "string",
            'enum'        => array(
                '000000000-0000-0000-0000-0000000000',
                '111111111-1111-1111-1111-1111111111'
            )
        );
    }

    protected function getSchemaData()
    {
        return json_decode(file_get_contents(__DIR__ . '/../Schema/image.json'), true);
    }

    public function test_Factory()
    {
        $schema = Schema::factory($this->getSchemaData());
        $property = Property::factory($this->getPropertyData());

        $operation = Operation::factory($schema, $property, OperationType::REPLACE);

        $this->assertEquals($schema, $operation->getSchema());
        $this->assertEquals($property->getPath(), $operation->getPath());
        $this->assertEquals($property->getValue(), $operation->getValue());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_Validate_Fails()
    {
        $operation = new Operation();
        $operation->setType('foo');
        $operation->validate();
    }
}
