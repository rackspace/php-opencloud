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

namespace OpenCloud\LoadBalancer\Resource;

use Guzzle\Http\Exception\ClientErrorResponseException;
use OpenCloud\Common\Resource\PersistentResource;

/**
 * Class that represents abstract functionality for Load Balancer resources
 *
 * @package OpenCloud\LoadBalancer\Resource
 */
abstract class AbstractResource extends PersistentResource
{
    public function refresh($id = null, $url = null)
    {
        if (!isset($this->id)) {
            return false;
        }

        try {
            return parent::refresh($id, $url);
        } catch (ClientErrorResponseException $e) {
            return false;
        }
    }

    protected function createJson()
    {
        $object = new \stdClass;

        foreach ($this->createKeys as $item) {
            $object->$item = $this->$item;
        }

        if ($top = $this->jsonName()) {
            $object = array($top => $object);
        }

        return $object;
    }

    protected function updateJson($params = array())
    {
        return $this->createJson();
    }

    public function name()
    {
        $classArray = explode('\\', get_class($this));

        return method_exists($this->getParent(), 'id')
            ? sprintf('%s-%s', end($classArray), $this->getParent()->id())
            : parent::name();
    }
}
