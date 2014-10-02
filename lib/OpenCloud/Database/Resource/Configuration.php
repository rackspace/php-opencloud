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

namespace OpenCloud\Database\Resource;

use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Resource\PersistentResource;
use OpenCloud\Database\Service;

/**
 * Configuration represents an configuration group for a tenant
 */
class Configuration extends PersistentResource
{
    public $id;
    public $datastore_name;
    public $datastore_version_id;
    public $datastore_version_name;
    public $description;
    public $instance_count;
    public $name;
    public $created;
    public $updated;
    public $values;

    protected static $json_name = 'configuration';
    protected static $url_resource = 'configurations';

    protected $createKeys = array(
        'name',
        'description',
        'values',
        'datastore'
    );

    /**
     * Patches a database configuration, replacing ONLY the values provided.
     */
    public function patch($params = array())
    {
        $json = json_encode($this->updateJson($params));
        $this->checkJsonError();

        return $this->getClient()->patch($this->url(), self::getJsonHeader(), $json)->send();
    }

    /**
     * Generates the JSON string for update()
     *
     * @return \@stdClass
     */
    protected function updateJson($params = array())
    {
        return (object) array(
            self::$json_name => $params
        );
    }

    /**
     * Returns a Collection of all instances using this configuration
     *
     * @return OpenCloud\Common\Collection\PaginatedIterator
     */
    public function instanceList()
    {
        return $this->getService()->resourceList('Instance', $this->getUrl('instances'));
    }
}
