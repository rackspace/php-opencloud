<?php

namespace OpenCloud\Images\Resource\Schema;

use OpenCloud\Common\ArrayAccess;

class Schema
{
    protected $name;
    protected $properties;
    protected $additionalProperties;
    protected $links;

    public static function factory(array $data)
    {
        $schema = new self();

        $schema->setName($data['name']);
        $schema->setProperties($data['properties']);
        $schema->setAdditionalProperties($data['additionalProperties']);
        $schema->setLinks($data['links']);

        return $schema;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setProperties(array $properties)
    {
        foreach ($properties as $name => $array) {
            $this->properties[$name] = Property::factory($name, $array);
        }
    }

    public function setAdditionalProperties(array $properties)
    {
        $this->additionalProperties = $properties;
    }

    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    public function propertyExists($property)
    {
        return isset($this->properties[$property]);
    }

    public function getProperty($property)
    {
        return $this->propertyExists($property) ? $this->properties[$property] : null;
    }

    public function getAdditionalProperty($value)
    {
        if (!empty($this->additionalProperties)) {
            return new Property($this->additionalProperties);
        }
        // no additional props allowed
        return false;
    }
}