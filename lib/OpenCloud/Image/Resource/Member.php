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

use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\Common\Exceptions\ForbiddenOperationException;
use OpenCloud\Common\Exceptions\ResourceNotFoundException;
use OpenCloud\Image\Enum\MemberStatus;

/**
 * Class that represents a Member which belongs to a Glance Image. In more general terms, an image member is a user who
 * has been granted access to an image. Normally, if an image is not public, only the owner can boot from the image.
 *
 * @package OpenCloud\Images\Resource
 */
class Member extends AbstractSchemaResource
{
    protected static $url_resource = 'members';
    protected static $json_name = '';
    protected static $json_collection_name = 'members';

    /**
     * @var array Enumerated types
     */
    private $allowedStates = array(
        MemberStatus::ACCEPTED,
        MemberStatus::PENDING,
        MemberStatus::REJECTED
    );

    /**
     * Update the status of a member, allowing them to fully access an image after being invited
     *
     * @param $status The eventual status this member wants.
     * @return \Guzzle\Http\Message\Response
     * @throws \OpenCloud\Common\Exceptions\ForbiddenOperationException
     * @throws \OpenCloud\Common\Exceptions\ResourceNotFoundException
     * @throws \Guzzle\Http\Exception\BadResponseException
     * @throws \InvalidArgumentException
     */
    public function updateStatus($status)
    {
        if (!in_array($status, $this->allowedStates)) {
            throw new \InvalidArgumentException(
                sprintf('Status must be one of these defined types: %s', implode($this->allowedStates, ','))
            );
        }

        $json = json_encode(array('status' => $status));

        $request = $this->getClient()->put($this->getUrl(), self::getJsonHeader(), $json);

        try {
            return $request->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();

            switch ($response->getStatusCode()) {
                case 403:
                    $exception = ForbiddenOperationException::factory($e);
                    break;
                case 404:
                    $exception = ResourceNotFoundException::factory($e);
                    break;
            }

            throw isset($exception) ? $exception : $e;
        }
    }

    /**
     * @return \Guzzle\Http\Message\Response
     */
    public function delete()
    {
        return $this->getClient()->delete($this->getUrl())->send();
    }
}
