<?php

namespace OpenCloud\Images\Resource;

use OpenCloud\Common\Constants\Header;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Resource\BaseResource;
use OpenCloud\Images\Resource\JsonPatch\Document as JsonDocument;
use OpenCloud\Images\Resource\JsonPatch\Operation as JsonOperation;
use OpenCloud\Images\Resource\Schema\Property;
use OpenCloud\Images\Resource\Schema\Schema;

class Image extends BaseResource implements ImageInterface
{
    protected static $url_resource = 'images';
    protected static $json_collection_name = 'images';

    public function update(array $params, Schema $schema = null)
    {
        $schema = $schema ?: $this->getService()->getImageSchema();

        $document = new JsonDocument();

        foreach ($params as $propertyName => $value) {

            // find property object
            if (!($property = $schema->getProperty($propertyName))) {
                // check whether additional properties are found
                if (false === ($property ==$schema->getAdditionalProperty($value))) {
                    throw new \RuntimeException();
                }
            }

            // do validation checks
            $property->setValue($value);
            $property->validate();

            // create JSON-patch operation
            $operation = JsonOperation::factory($schema, $property);

            // add to JSON document
            $document->addOperation($operation);
        }

        // create request
        $body = $document->toString();

        return $this->getClient()
            ->patch($this->getUrl(), $this->getService()->getPatchHeaders(), $body)
            ->send();
    }
}