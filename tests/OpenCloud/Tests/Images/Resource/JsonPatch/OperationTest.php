<?php

namespace OpenCloud\Tests\Images\Resource\JsonPatch;

use OpenCloud\Images\Resource\JsonPatch\Operation;
use OpenCloud\Images\Resource\Schema\Property;
use OpenCloud\Images\Resource\Schema\Schema;
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

        $operation = Operation::factory($schema, $property);

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