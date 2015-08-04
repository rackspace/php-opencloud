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
 * This class represents a User in the Rackspace "Red Dwarf"
 * database-as-a-service product.
 */
class User extends PersistentResource
{
    /** @var string The user name */
    public $name;

    /** @var string The user's password  */
    public $password;

    /** @var array A list of database names assigned to the user */
    public $databases = array();

    protected static $json_name = 'user';
    protected static $url_resource = 'users';

    /**
     * Creates a new database object
     *
     * Unlike other objects (Servers, DataObjects, etc.), passing a database
     * name to the constructor does *not* pull information from the database.
     * For example, if you pass an ID to the `Server()` constructor, it will
     * attempt to retrieve the information on that server from the service,
     * and will return an error if it is not found. However, the Cloud
     * Users service does not permit retrieval of information on
     * individual databases (only via Collection), and thus passing in a
     * name via the `$info` parameter only creates an in-memory object that
     * is not necessarily tied to an actual database.
     *
     * @param Instance $instance the parent DbService\Instance of the database
     * @param mixed    $info     if an array or object, treated as properties to set;
     *                           if a string, treated as the database name
     * @param array    $db       a list of database names to associate with the User
     * @return void
     * @throws UserNameError if `$info` is not a string, object, or array
     */
    public function __construct(Instance $instance, $info = null, $db = array())
    {
        $this->setParent($instance);

        if (!empty($db)) {
            $this->databases = $db;
        }

        // Lazy...
        if (is_string($info)) {
            $info = array('name' => $info);
        }

        return parent::__construct($instance->getService(), $info);
    }

    /**
     * Returns name of this user. Because it's so important (i.e. as an
     * identifier), it will throw an error if not set/empty.
     *
     * @return type
     * @throws Exceptions\DatabaseNameError
     */
    public function getName()
    {
        if (empty($this->name)) {
            throw new Exceptions\DatabaseNameError(
                Lang::translate('This user does not have a name yet')
            );
        }

        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function primaryKeyField()
    {
        return 'name';
    }

    /**
     * Adds a new database to the list of databases for the user
     *
     * @api
     * @param string $dbname the database name to be added
     * @return void
     */
    public function addDatabase($dbname)
    {
        $this->databases[] = $dbname;
    }

    /**
     * {@inheritDoc}
     */
    public function update($params = array())
    {
        return $this->noUpdate();
    }

    /**
     * Deletes a database user
     *
     * @api
     * @return \OpenCloud\HttpResponse
     * @throws UserDeleteError if HTTP response is not Success
     */
    public function delete()
    {
        return $this->getClient()->delete($this->url())->send();
    }

    /**
     * {@inheritDoc}
     */
    protected function createJson()
    {
        $user = (object) array(
            'name'      => $this->name,
            'password'  => $this->password,
            'databases' => array()
        );

        foreach ($this->databases as $dbName) {
            $user->databases[] = (object) array('name' => $dbName);
        }

        return (object) array(
            'users' => array($user)
        );
    }

    /**
     * Grant access to a set of one or more databases to a user.
     *
     * @param []string $databases An array of one or more database names that this user will be granted access to. For
     *                            example, ['foo', 'bar'] or ['baz'] are valid inputs.
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function grantDbAccess(array $databases)
    {
        $json = [];

        foreach ($databases as $database) {
            $json[] = ['name' => $database];
        }

        $json = ['databases' => $json];

        $url = $this->getUrl('databases');
        $headers = self::getJsonHeader();
        $body = json_encode($json);

        return $this->getClient()->put($url, $headers, $body)->send();
    }
}
