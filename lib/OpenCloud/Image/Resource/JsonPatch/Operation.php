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

namespace OpenCloud\Image\Resource\JsonPatch;

use OpenCloud\Image\Enum\OperationType as Type;
use OpenCloud\Image\Resource\Schema\Property;
use OpenCloud\Image\Resource\Schema\Schema;

/**
 * Class that represents a JSON Patch operation. It utilizes the JSON pointer syntax, in line with RFC 6902.
 *
 * @see http://tools.ietf.org/html/rfc6901
 * @package OpenCloud\Images\Resource\JsonPatch
 */
class Operation
{
    /**
     * Allowed operation types
     *
     * @var array
     * @see http://tools.ietf.org/html/rfc6902#section-4
     */
    private $allowedTypes = array(
        Type::ADD, Type::REMOVE, Type::REPLACE,
        Type::MOVE, Type::COPY, Type::TEST
    );

    /** @var Schema The JSON schema this operation is acting on */
    protected $schema;

    /** @var string The type of operation */
    protected $type;

    /** @var string The JSON pointer value */
    protected $path;

    /** @var mixed The value for this JSON property */
    protected $value;

    /**
     * @param Schema   $schema
     * @param Property $property
     * @param string $operationType
     * @return Operation
     */
    public static function factory(Schema $schema, Property $property, $operationType)
    {
        $operation = new self();

        $operation->setType($operationType);
        $operation->setSchema($schema);
        $operation->setPath($property->getPath());
        $operation->setValue($property->getValue());

        return $operation;
    }

    /**
     * @param $type string
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
     * @param Schema $schema
     */
    public function setSchema(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return Schema
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * Validates that this operation is of an allowed type
     *
     * @throws \RuntimeException if not
     */
    public function validate()
    {
        if (!in_array($this->type, $this->allowedTypes)) {
            throw new \RuntimeException(sprintf("%s is not an allowed JSON PATCH operation type", $this->type));
        }
    }
}
