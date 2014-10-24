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

namespace OpenCloud\Image\Resource;

use OpenCloud\Image\Enum\OperationType;
use OpenCloud\Image\Resource\JsonPatch\Document as JsonDocument;
use OpenCloud\Image\Resource\JsonPatch\Operation as JsonOperation;
use OpenCloud\Image\Resource\Schema\Schema;

/**
 * Class that represents a Glance Image. In more general terms, a virtual machine image is a single file which contains
 * a virtual disk that has an installed bootable operating system. In the Cloud Images API, an image is represented by
 * a JSON-encoded data structure (the image schema) and its raw binary data (the image file).
 *
 * @package OpenCloud\Images\Resource
 */
class Image extends AbstractSchemaResource implements ImageInterface
{
    protected static $url_resource = 'images';
    protected static $json_name = '';
    protected static $json_collection_name = 'images';

    /**
     * Update this resource
     *
     * @param array  $params
     * @param Schema $schema
     * @return \Guzzle\Http\Message\Response
     * @throws \RuntimeException
     */
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

            // decide operation type
            if (!$value) {
                $operationType = OperationType::REMOVE;
            } elseif ($this->offsetExists($propertyName)) {
                $operationType = OperationType::REPLACE;
            } else {
                $operationType = $schema->decideOperationType($property);
            }

            // create JSON-patch operation
            $operation = JsonOperation::factory($schema, $property, $operationType);

            // add to JSON document
            $document->addOperation($operation);
        }

        // create request
        $body = $document->toString();

        return $this->getClient()
            ->patch($this->getUrl(), $this->getService()->getPatchHeaders(), $body)
            ->send();
    }

    /**
     * Refresh this resource
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function refresh()
    {
        $response = $this->getClient()->get($this->getUrl())->send();

        $this->setData($response->json());

        return $response;
    }

    /**
     * Delete this resource
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function delete()
    {
        return $this->getClient()->delete($this->getUrl())->send();
    }

    /**
     * List the members of this image
     *
     * @param array $params
     * @return mixed
     */
    public function listMembers(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Member::resourceName())->setQuery($params);

        return $this->getService()->resourceList('Member', $url, $this);
    }

    /**
     * Iterator use only
     *
     * @param $data
     * @return mixed
     */
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

    /**
     * Get a member belonging to this image
     *
     * @param $memberId
     * @return mixed
     */
    public function getMember($memberId)
    {
        $url = clone $this->getUrl();
        $url->addPath('members');
        $url->addPath((string) $memberId);

        $data = $this->getClient()->get($url)->send()->json();

        return $this->member($data);
    }

    /**
     * Add a member to this image
     *
     * @param $tenantId
     * @return \Guzzle\Http\Message\Response
     */
    public function createMember($tenantId)
    {
        $url = $this->getUrl();
        $url->addPath('members');

        $json = json_encode(array('member' => $tenantId));
        return $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
    }

    /**
     * Delete a member from this image
     *
     * @param $tenantId
     * @return \Guzzle\Http\Message\Response
     */
    public function deleteMember($tenantId)
    {
        $url = $this->getUrl();
        $url->addPath('members');
        $url->addPath((string)$tenantId);

        return $this->getClient()->delete($url)->send();
    }

    /**
     * Add a tag to this image
     *
     * @param string $tag
     * @return \Guzzle\Http\Message\Response
     */
    public function addTag($tag)
    {
        $url = clone $this->getUrl();
        $url->addPath('tags')->addPath((string) $tag);

        return $this->getClient()->put($url)->send();
    }

    /**
     * Delete a tag from this image
     *
     * @param $tag
     * @return \Guzzle\Http\Message\Response
     */
    public function deleteTag($tag)
    {
        $url = clone $this->getUrl();
        $url->addPath('tags')->addPath((string) $tag);

        return $this->getClient()->delete($url)->send();
    }
}
