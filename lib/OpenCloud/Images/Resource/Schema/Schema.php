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

        $schema->name = $data['name'];
        $schema->properties = new ArrayAccess($data['properties']);
        $schema->additionalProperties = $data['additionalProperties'];
        $schema->links = $data['links'];

        return $schema;
    }

    public function propertyExists($property)
    {
        return $this->properties->offsetExists($property);
    }

    public function getProperty($property)
    {
        return $this->propertyExists($property) ? $this->properties->offsetGet($property) : null;
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