<?php

namespace OpenCloud\Images\Resource;

use OpenCloud\Images\Resource\JsonPatch\Document as JsonDocument;
use OpenCloud\Images\Resource\JsonPatch\Operation as JsonOperation;
use OpenCloud\Images\Resource\Schema\Schema;

class Image extends AbstractSchemaResource implements ImageInterface
{
    protected static $url_resource = 'images';
    protected static $json_name = '';
    protected static $json_collection_name = 'images';

    public function update(array $params, Schema $schema = null)
    {
        $schema = $schema ?: $this->getService()->getImageSchema();

        $document = new JsonDocument();

        foreach ($params as $propertyName => $value) {

            // find property object
            if (!($property = $schema->getProperty($propertyName))) {
                // check whether additional properties are found
                if (false === ($property = $schema->validateAdditionalProperty($value))) {
                    throw new \RuntimeException(
                        'If a property does not exist in the schema, the `additionalProperties` property must be set'
                    );
                }
            }

            // do validation checks
            $property->setName($propertyName);
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

    public function refresh()
    {
        $response = $this->getClient()->get($this->getUrl())->send();

        $this->setData($response->json());

        return $response;
    }

    public function delete()
    {
        return $this->getClient()->delete($this->getUrl())->send();
    }

    public function listMembers(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Member::resourceName())->setQuery($params);

        return $this->resourceList('Image', $url);
    }

    public function member($data)
    {
        $data = (array) $data;

        $member = $this->getService()->resource('Member', null, $this);
        $member->setData($data);

        if (isset($data['member_id'])) {
            $member->setId($data['member_id']);
        }

        return $member;
    }

    public function getMember($memberId)
    {
        $url = clone $this->getUrl();
        $url->addPath('members')->addPath((string) $memberId);

        $data = $this->getClient()->get($url)->send()->json();

        return $this->member($data);
    }

    public function createMember($tenantId)
    {
        $json = json_encode(array('member' => $tenantId));
        return $this->getClient()->post($this->getUrl(), self::getJsonHeader(), $json)->send();
    }
}