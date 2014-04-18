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

namespace OpenCloud\Image\Resource\Schema;

use OpenCloud\Image\Enum\OperationType;
use OpenCloud\Image\Enum\Schema as SchemaEnum;

/**
 * Class that represents a JSON schema document
 *
 * @package OpenCloud\Images\Resource\Schema
 */
class Schema extends AbstractSchemaItem
{
    /** @var string Name of schema */
    protected $name;

    /** @var array Properties that this schema possesses */
    protected $properties;

    /**
     * If set, this determines the template that all additional (i.e. undefined) properties must adhere to
     *
     * @var null|Property
     */
    protected $additionalProperties;

    /** @var array Links for this schema */
    protected $links;

    /**
     * @param array $data
     * @return Schema
     */
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

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $name => $array) {
            $array[SchemaEnum::NAME] = $name;
            $this->properties[$name] = Property::factory($array);
        }
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setAdditionalProperties(array $properties)
    {
        $this->additionalProperties = $properties;
    }

    /**
     * @return bool|Property
     */
    public function getAdditionalProperties()
    {
        if (!empty($this->additionalProperties)) {
            return Property::factory($this->additionalProperties);
        }

        return false;
    }

    /**
     * @param array $links
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Check whether a property exists
     *
     * @param $property The name of the property
     * @return bool
     */
    public function propertyExists($property)
    {
        return isset($this->properties[$property]);
    }

    /**
     * Retrieve a property
     *
     * @param $property The name of the property
     * @return null|Property
     */
    public function getProperty($property)
    {
        return $this->propertyExists($property) ? $this->properties[$property] : null;
    }

    /**
     * Based on this schema, decide the most appropriate operation type for a given property
     *
     * @param Property $property The property being performed on
     * @return string
     */
    public function decideOperationType(Property $property)
    {
        $name = $property->getName();

        return ($this->propertyExists($name)) ? OperationType::REPLACE : OperationType::ADD;
    }

    /**
     * Check whether an additional property is allowed and its type is valid
     *
     * @param $value         The value trying to be set
     * @return bool|Property
     */
    public function validateAdditionalProperty($value)
    {
        if ($property = $this->getAdditionalProperties()) {
            $property->setValue($value);

            return ($property->validate() === true) ? $property : false;
        }

        return false;
    }
}
