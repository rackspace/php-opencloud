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

namespace OpenCloud\Images\Resource\Schema;

use OpenCloud\Images\Enum\Schema as SchemaEnum;
use OpenCloud\Images\Resource\JsonPatch\Encoder;

class Property extends AbstractSchemaItem
{
    const DELIMETER = '#';

    protected $name;
    protected $description;
    protected $type;
    protected $enum;
    protected $pattern;
    protected $items;
    protected $value;

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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setEnum($enum)
    {
        $this->enum = $enum;
    }

    public function getEnum()
    {
        return $this->enum;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setItems($data)
    {
        $this->items = Schema::factory($data);
    }

    public function getItems()
    {
        return $this->items;
    }

    protected function preparePattern($pattern)
    {
        return self::DELIMETER . (string) $pattern . self::DELIMETER;
    }

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


    public function getPath()
    {
        return sprintf("/%s", Encoder::transform($this->name));
    }
}