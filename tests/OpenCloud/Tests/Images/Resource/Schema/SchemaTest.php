<?php

namespace OpenCloud\Tests\Images\Resource\Schema;

use OpenCloud\Images\Enum\OperationType;
use OpenCloud\Images\Resource\Schema\Property;
use OpenCloud\Images\Resource\Schema\Schema;
use OpenCloud\Tests\OpenCloudTestCase;

class SchemaTest extends OpenCloudTestCase
{
    protected function getSchema()
    {
        return json_decode(file_get_contents(__DIR__ . '/image.json'), true);
    }

    public function test_Factory()
    {
        $schema = Schema::factory($this->getSchema());

        $this->assertInstanceOf('OpenCloud\Images\Resource\Schema\Schema', $schema);

        $this->assertEquals('image', $schema->getName());

        $links = $schema->getLinks();
        $this->assertEquals('{self}', $links[0]['href']);
        $this->assertEquals('self', $links[0]['rel']);

        $properties = $schema->getProperties();
        $this->assertInstanceOf('OpenCloud\Images\Resource\Schema\Property', current($properties));

        $this->assertTrue($schema->propertyExists('visibility'));
        $this->assertFalse($schema->propertyExists('foo'));

        $this->assertInstanceOf('OpenCloud\Images\Resource\Schema\Property', $schema->getProperty('visibility'));
        $this->assertNull($schema->getProperty('foo'));
    }

    public function test_Additional_Properties()
    {
        $schema1 = Schema::factory($this->getSchema());

        $addProps = $schema1->getAdditionalProperties();
        $this->assertInstanceOf('OpenCloud\Images\Resource\Schema\Property', $addProps);
        $this->assertEquals('string', $addProps->getType());

        $this->assertFalse(Schema::factory(array())->getAdditionalProperties());
    }

    public function test_Deciding_Operation_Type()
    {
        $json1 = '{"properties": {"foo": {"type": "string"}, "baz": {"type": "string"}}}';
        $schema1 = Schema::factory(json_decode($json1, true));

        $property1 = Property::factory(array('type' => 'string', 'name' => 'foo'));
        $this->assertEquals(OperationType::REPLACE, $schema1->decideOperationType($property1));

        $property2 = Property::factory(array('type' => 'string', 'name' => 'username'));
        $this->assertEquals(OperationType::ADD, $schema1->decideOperationType($property2));
    }
}