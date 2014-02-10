<?php

namespace OpenCloud\Images\Resource\Schema;

use OpenCloud\Images\Enum\OperationType;
use OpenCloud\Images\Enum\Schema as SchemaEnum;

class Schema extends AbstractSchemaItem
{
    protected $name;
    protected $properties;
    protected $additionalProperties;
    protected $links;

    public static function factory(array $data)
    {
        $schema = new self();

        $schema->setName(self::stockProperty($data, SchemaEnum::NAME));

        if (isset($data[SchemaEnum::LINKS])) {
            $schema->setLinks($data[SchemaEnum::LINKS]);
        }

        if (isset($data[SchemaEnum::PROPERTIES])) {
            $schema->setProperties($data[SchemaEnum::PROPERTIES]);
        }

        if (isset($data[SchemaEnum::ADDITIONAL_PROPERTIES])) {
            $schema->setAdditionalProperties($data[SchemaEnum::ADDITIONAL_PROPERTIES]);
        }

        return $schema;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setProperties(array $properties)
    {
        foreach ($properties as $name => $array) {
            $array[SchemaEnum::NAME] = $name;
            $this->properties[$name] = Property::factory($array);
        }
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setAdditionalProperties(array $properties)
    {
        $this->additionalProperties = $properties;
    }

    public function getAdditionalProperties()
    {
        if (!empty($this->additionalProperties)) {
            return Property::factory($this->additionalProperties);
        }

        return false;
    }

    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function propertyExists($property)
    {
        return isset($this->properties[$property]);
    }

    public function getProperty($property)
    {
        return $this->propertyExists($property) ? $this->properties[$property] : null;
    }

    public function decideOperationType(Property $property)
    {
        $name = $property->getName();

        return ($this->propertyExists($name)) ? OperationType::REPLACE : OperationType::ADD;
    }

    public function validateAdditionalProperty($value)
    {
        if ($property = $this->getAdditionalProperties()) {
            $property->setValue($value);
            return ($property->validate() === true) ? $property : false;
        }

        return false;
    }
}