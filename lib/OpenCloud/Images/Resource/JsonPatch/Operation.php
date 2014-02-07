<?php

namespace OpenCloud\Images\Resource\JsonPatch;

use OpenCloud\Images\Enum\OperationType as Type;
use OpenCloud\Images\Resource\Schema\Property;
use OpenCloud\Images\Resource\Schema\Schema;

class Operation
{
    private $allowedTypes = array(
        Type::ADD, Type::REMOVE, Type::REPLACE,
        Type::MOVE, Type::COPY, Type::TEST
    );

    protected $schema;
    protected $type;
    protected $path;
    protected $value;

    public static function factory(Schema $schema, Property $property)
    {
        $operation = new self();

        $operation->setType($schema->decideOperationType($property));
        $operation->setSchema($schema);
        $operation->setPath($property->getPath());
        $operation->setValue($property->getValue());

        return $operation;
    }

    public function setType($type)
    {
       $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setSchema(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function validate()
    {
        if (!in_array($this->type, $this->allowedTypes)) {
            throw new \RuntimeException(sprintf("%s is not an allowed JSON PATCH operation type", $this->type));
        }
    }
}