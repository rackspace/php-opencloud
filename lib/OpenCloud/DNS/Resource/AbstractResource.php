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

namespace OpenCloud\DNS\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Resource\PersistentResource;

abstract class AbstractResource extends PersistentResource
{
    public function create($params = array())
    {
        $body = Formatter::decode(parent::create($params));

        return new AsyncResponse($this->getService(), $body);
    }

    public function update($params = array())
    {
        $response = parent::update($params);
        $body = Formatter::decode($response);

        return new AsyncResponse($this->getService(), $body);
    }

    public function delete()
    {
        $body = Formatter::decode(parent::delete());

        return new AsyncResponse($this->getService(), $body);
    }

    protected function createJson()
    {
        if (!$this->getCreateKeys()) {
            throw new Exceptions\CreateError(
                Lang::translate('Missing [createKeys]')
            );
        }

        return (object) array(
            self::jsonCollectionName() => array(
                $this->getJson($this->getCreateKeys())
            )
        );
    }

    protected function updateJson($params = array())
    {
        if (!$this->getUpdateKeys()) {
            throw new Exceptions\UpdateError(
                Lang::translate('Missing [updateKeys]')
            );
        }

        return $this->getJson($this->getUpdateKeys());
    }

    /**
     * returns JSON based on $keys
     *
     * @param array $keys list of items to include
     * @return stdClass
     */
    private function getJson($keys)
    {
        $object = new \stdClass;
        foreach ($keys as $item) {
            if (!empty($this->$item)) {
                $object->$item = $this->$item;
            }
        }

        return $object;
    }

    /**
     * Retrieve the keys which are required when the object is created.
     *
     * @return array|false
     */
    public function getCreateKeys()
    {
        return (!empty($this->createKeys)) ? $this->createKeys : false;
    }

    /**
     * Retrieve the keys which are required when the object is updated.
     *
     * @return array|false
     */
    public function getUpdateKeys()
    {
        return (!empty($this->updateKeys)) ? $this->updateKeys : false;
    }
}
