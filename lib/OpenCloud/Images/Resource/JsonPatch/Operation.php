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

    public function factory(Schema $schema, Property $property)
    {

    }
}