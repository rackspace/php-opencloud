<?php

namespace OpenCloud\Images\Resource\Schema;

abstract class AbstractSchemaItem
{
    protected static function stockProperty(array $data, $property)
    {
        return isset($data[$property]) ? $data[$property] : null;
    }
}