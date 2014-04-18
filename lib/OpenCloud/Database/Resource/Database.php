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

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Resource\PersistentResource;

/**
 * This class represents a Database in the Rackspace "Red Dwarf"
 * database-as-a-service product.
 */
class Database extends PersistentResource
{
    /** @var string */
    public $name;

    protected static $json_collection_name = 'databases';
    protected static $url_resource = 'databases';

    public function __construct(Instance $instance, $info = null)
    {
        $this->setParent($instance);

        // Catering for laziness
        if (is_string($info)) {
            $info = array('name' => $info);
        }

        return parent::__construct($instance->getService(), $info);
    }

    /**
     * Returns name of this database. Because it's so important (i.e. as an
     * identifier), it will throw an error if not set/empty.
     *
     * @return type
     * @throws Exceptions\DatabaseNameError
     */
    public function getName()
    {
        if (empty($this->name)) {
            throw new Exceptions\DatabaseNameError(
                Lang::translate('The database does not have a Url yet')
            );
        }

        return $this->name;
    }

    public function primaryKeyField()
    {
        return 'name';
    }

    /**
     * Returns the Instance of the database
     *
     * @return Instance
     */
    public function instance()
    {
        return $this->getParent();
    }

    /**
     * Creates a new database
     *
     * @api
     * @param array $params array of attributes to set prior to Create
     * @return \OpenCloud\HttpResponse
     */
    public function create($params = array())
    {
        // target the /databases subresource
        $url = $this->getParent()->url('databases');

        if (isset($params['name'])) {
            $this->name = $params['name'];
        }

        $json = json_encode($this->createJson($params));
        $this->checkJsonError();

        // POST it off
        return $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
    }

    /**
     * Updates an existing database
     *
     * @param array $params ignored
     * @throws DatabaseUpdateError always; updates are not permitted
     * @return void
     */
    public function update($params = array())
    {
        return $this->noUpdate();
    }

    /**
     * Returns the JSON object for creating the database
     */
    protected function createJson(array $params = array())
    {
        $database = (object) array_merge(array('name' => $this->getName(), $params));

        return (object) array(
            'databases' => array($database)
        );
    }
}
