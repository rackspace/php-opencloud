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

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\PersistentObject;

abstract class AbstractResource extends PersistentObject
{
    public function createJson()
    {
        foreach (static::$requiredKeys as $requiredKey) {
            if (!$this->getProperty($requiredKey)) {
                throw new Exceptions\CreateError(sprintf(
                    "%s is required to create a new %s", $requiredKey, get_class()
                ));
            }
        }

        $object = new \stdClass;

        foreach (static::$emptyObject as $key) {
            if ($property = $this->getProperty($key)) {
                $object->$key = $property;
            }
        }

        return $object;
    }

    protected function updateJson($params = array())
    {
        $object = (object) $params;

        foreach (static::$requiredKeys as $requiredKey) {
            if (!$this->getProperty($requiredKey)) {
                throw new Exceptions\UpdateError(sprintf(
                    "%s is required to update a %s", $requiredKey, get_class($this)
                ));
            }
        }

        return $object;
    }

    /**
     * Retrieves a collection of resource objects.
     *
     * @access public
     * @return void
     */
    public function listAll()
    {
        return $this->getService()->collection(get_class($this), $this->url());
    }

    /**
     * Test the validity of certain parameters for the resource.
     *
     * @access public
     * @param array $params (default: array())
     * @param bool  $debug  (default: false)
     * @return void
     */
    public function testParams($params = array(), $debug = false)
    {
        $json = json_encode((object) $params);

        // send the request
        $response = $this->getService()
            ->getClient()
            ->post($this->testUrl($debug), self::getJsonHeader(), $json)
            ->send();

        return Formatter::decode($response);
    }

    /**
     * Test the validity of an existing resource.
     *
     * @access public
     * @param bool $debug (default: false)
     * @return void
     */
    public function test($debug = false)
    {
        $json = json_encode($this->updateJson());
        $this->checkJsonError();

        $response = $this->getClient()
            ->post($this->testExistingUrl($debug), self::getJsonHeader(), $json)
            ->send();

        return Formatter::decode($response);
    }
}
