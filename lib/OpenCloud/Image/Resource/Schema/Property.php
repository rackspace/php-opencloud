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

use OpenCloud\Image\Enum\Schema as SchemaEnum;
use OpenCloud\Image\Resource\JsonPatch\Encoder;

/**
 * Class that represents an individual property in a JSON schema
 *
 * @package OpenCloud\Images\Resource\Schema
 */
class Property extends AbstractSchemaItem
{
    const DELIMETER = '#';

    /** @var string Name of property */
    protected $name;

    /** @var string Description of property */
    protected $description;

    /** @var string Type of property (e.g. string, array) */
    protected $type;

    /** @var array Enumerated types that values must adhere to */
    protected $enum;

    /** @var string Regex pattern that values must adhere to */
    protected $pattern;

    /** @var array Array items that this property may possess */
    protected $items;

    /** @var mixed This property's value */
    protected $value;

    /**
     * @param array $data
     * @return Property
     */
    public static function factory(array $data = array())
    {
        $property = new self();

        $property->setName(self::stockProperty($data, SchemaEnum::NAME));
        $property->setDescription(self::stockProperty($data, SchemaEnum::DESCRIPTION));
        $property->setType(self::stockProperty($data, SchemaEnum::TYPE));
        $property->setEnum(self::stockProperty($data, SchemaEnum::ENUM));
        $property->setPattern(self::stockProperty($data, SchemaEnum::PATTERN));

        if (isset($data[SchemaEnum::ITEMS])) {
            // handle sub-schemas
            $property->setItems($data[SchemaEnum::ITEMS]);
        }

        return $property;
    }

    /**
     * @param $name
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
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $enum
     */
    public function setEnum($enum)
    {
        $this->enum = $enum;
    }

    /**
     * @return array
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * @param $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $data
     */
    public function setItems($data)
    {
        $this->items = Schema::factory($data);
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Prepare the given pattern for Regex functions
     *
     * @param $pattern
     * @return string
     */
    protected function preparePattern($pattern)
    {
        return self::DELIMETER . (string) $pattern . self::DELIMETER;
    }

    /**
     * Validate the current value and ensure that it adheres to correct formatting, etc.
     *
     * @return bool
     */
    public function validate()
    {
        // deal with enumerated types
        if (!empty($this->enum)) {
            return in_array($this->value, $this->enum);
        }

        // handle patterns
        if ($this->pattern) {
            return (bool) preg_match($this->preparePattern($this->pattern), $this->value);
        }

        // handle type
        if ($this->type) {
            return $this->type === gettype($this->value);
        }

        return true;
    }

    /**
     * Get the JSON pointer for this property
     *
     * @return string
     */
    public function getPath()
    {
        return sprintf("/%s", Encoder::transform($this->name));
    }
}
