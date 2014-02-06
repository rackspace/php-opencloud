<?php

namespace OpenCloud\Images\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Images\Resource\JsonPatch\Document as JsonDocument;
use OpenCloud\Images\Resource\JsonPatch\Operation as JsonOperation;
use OpenCloud\Images\Resource\Schema\Property;

class Image extends PersistentObject implements ImageInterface
{
    protected static $url_resource = 'images';
    protected static $json_collection_name = 'images';

    /** @var Schema */
    protected $schema;

    public function update(array $params)
    {
        $this->schema = $this->getService()->getImageSchema();

        $document = new JsonDocument();

        foreach ($params as $propertyName => $value) {

            // find property object
            if (!($property = $this->schema->getProperty($propertyName))) {
                // check whether additional properties are found
                if (false === ($property ==$this->schema->getAdditionalProperty($value))) {
                    throw new \RuntimeException();
                }
            }

            // do validation checks
            $property->setValue($value);
            $property->validate();

            // create JSON-patch operation
            $operation = JsonOperation::factory($this->schema, $property);

            // add to JSON document
            $document->addOperation($operation);
        }

        // create request

    }
}