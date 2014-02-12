<?php

namespace OpenCloud\Tests\Images\Resource\Schema;

use OpenCloud\Images\Resource\Schema\Property;
use OpenCloud\Tests\OpenCloudTestCase;

class PropertyTest extends OpenCloudTestCase
{
    protected function getData()
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

    public function test_Factory()
    {
        $data = $this->getData();
        $property = Property::factory($data);

        $this->assertEquals($data['name'], $property->getName());
        $this->assertEquals($data['description'], $property->getDescription());
        $this->assertEquals($data['pattern'], $property->getPattern());
        $this->assertEquals($data['type'], $property->getType());
        $this->assertEquals($data['enum'], $property->getEnum());
    }

    public function test_Items()
    {
        $fullData = json_decode(file_get_contents(__DIR__ . '/images.json'), true);
        $propertyData = $fullData['properties']['images'];
        $propertyData['name'] = 'images';

        $property = Property::factory($propertyData);

        $subSchema = $property->getItems();

        $this->assertInstanceOf('OpenCloud\Images\Resource\Schema\Schema', $subSchema);
        $this->assertEquals('image', $subSchema->getName());
        $this->assertTrue($subSchema->propertyExists('status'));
        $this->assertEquals("List of strings related to the image", $subSchema->getProperty('tags')->getDescription());
    }

    public function test_Value()
    {
        $property = Property::factory($this->getData());

        $uuid = '000000000-0000-0000-0000-0000000000';

        $property->setValue($uuid);
        $this->assertEquals($uuid, $property->getValue());

        $this->assertTrue($property->validate());
    }

    public function test_Enum_Validation()
    {
        $property = Property::factory($this->getData());

        $property->setValue('foo');
        $this->assertFalse($property->validate());
    }

    public function test_Pattern_Validation()
    {
        $data = $this->getData();
        unset($data['enum']);

        $property = Property::factory($data);

        $property->setValue('foo');
        $this->assertFalse($property->validate());
    }

    public function test_Type_Validation()
    {
        $data = $this->getData();
        unset($data['enum'], $data['pattern']);

        $property = Property::factory($data);

        $property->setValue(12345);
        $this->assertFalse($property->validate());
    }

    public function test_Successful_Validation()
    {
        $data = $this->getData();
        unset($data['enum'], $data['pattern'], $data['type']);

        $property = Property::factory($data);

        $property->setValue('foo');
        $this->assertTrue($property->validate());
    }

    public function test_Get_Path()
    {
        $data = $this->getData();
        unset($data['enum'], $data['pattern']);

        $property = Property::factory($data);

        $this->assertEquals('/id', $property->getPath());
    }
}