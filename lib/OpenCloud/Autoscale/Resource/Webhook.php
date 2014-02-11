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

namespace OpenCloud\Autoscale\Resource;

/**
 * Description of Webhook
 *
 * @link
 */
class Webhook extends AbstractResource
{
    public $id;
    public $name;
    public $metadata;
    public $links;

    protected static $json_name = 'webhook';
    protected static $url_resource = 'webhooks';

    public $createKeys = array(
        'name',
        'metadata'
    );

    public function createJson()
    {
        $object = new \stdClass;
        $object->name = $this->name;
        $object->metadata = $this->metadata;

        return $object;
    }
}
