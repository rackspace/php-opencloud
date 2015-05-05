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
use OpenCloud\Common\Resource\NovaResource;
use OpenCloud\Compute\Resource\Flavor;
use OpenCloud\Database\Service;

/**
 * Instance represents an instance of DbService, similar to a Server in a
 * Compute service
 */
class Instance extends NovaResource
{
    public $id;
    public $name;
    public $status;
    public $links;
    public $hostname;
    public $volume;
    public $created;
    public $updated;
    public $flavor;
    public $backupRef;

    protected static $json_name = 'instance';
    protected static $url_resource = 'instances';

    private $_databases; // used to Create databases simultaneously
    private $_users; // used to Create users simultaneously

    /**
     * Creates a new instance object
     *
     * This could use the default constructor, but we want to make sure that
     * the volume attribute is an object.
     *
     * @param \OpenCloud\DbService $service the DbService object associated
     *                                      with this
     * @param mixed                $info    the ID or array of info for the object
     */
    public function __construct(Service $service, $info = null)
    {
        $this->volume = new \stdClass;

        return parent::__construct($service, $info);
    }

    /**
     * Restarts the database instance
     *
     * @api
     * @returns \OpenCloud\HttpResponse
     */
    public function restart()
    {
        return $this->action($this->restartJson());
    }

    /**
     * Resizes the database instance (sets RAM)
     *
     * @api
     * @param \OpenCloud\Compute\Flavor $flavor a flavor object
     * @returns \OpenCloud\HttpResponse
     */
    public function resize(Flavor $flavor)
    {
        return $this->action($this->resizeJson($flavor->id));
    }

    /**
     * Resizes the volume associated with the database instance (disk space)
     *
     * @api
     * @param integer $newvolumesize the size of the new volume, in gigabytes
     * @return \OpenCloud\HttpResponse
     */
    public function resizeVolume($newvolumesize)
    {
        return $this->action($this->resizeVolumeJson($newvolumesize));
    }

    /**
     * Enables the root user for the instance
     *
     * @api
     * @return User the root user, including name and password
     * @throws InstanceError if HTTP response is not Success
     */
    public function enableRootUser()
    {
        $response = $this->getClient()->post($this->getUrl('root'))->send();
        $body = Formatter::decode($response);

        return (isset($body->user)) ? new User($this, $body->user) : false;
    }

    /**
     * Returns TRUE if the root user is enabled
     *
     * @api
     * @return boolean TRUE if the root user is enabled; FALSE otherwise
     * @throws InstanceError if HTTP status is not Success
     */
    public function isRootEnabled()
    {
        $response = $this->getClient()->get($this->url('root'))->send();
        $body = Formatter::decode($response);

        return !empty($body->rootEnabled);
    }

    /**
     * Returns a new Database object
     *
     * @param string $name the database name
     * @return Database
     */
    public function database($name = '')
    {
        return new Database($this, $name);
    }

    /**
     * Returns a new User object
     *
     * @param string $name      the user name
     * @param array  $databases a simple array of database names
     * @return User
     */
    public function user($name = '', $databases = array())
    {
        return new User($this, $name, $databases);
    }

    /**
     * Returns a Collection of all databases in the instance
     *
     * @return OpenCloud\Common\Collection\PaginatedIterator
     */
    public function databaseList()
    {
        return $this->getService()->resourceList('Database', $this->getUrl('databases'), $this);
    }

    /**
     * Returns a Collection of all users in the instance
     *
     * @return OpenCloud\Common\Collection\PaginatedIterator
     */
    public function userList()
    {
        return $this->getService()->resourceList('User', $this->getUrl('users'), $this);
    }

    /**
     * Returns a Collection of all backups for the instance
     *
     * @return OpenCloud\Common\Collection\PaginatedIterator
     */
    public function backupList()
    {
        return $this->getService()->resourceList('Backup', $this->getUrl('backups'), $this);
    }

    /**
     * Creates a backup for the given instance
     *
     * @api
     * @param array $params - an associate array of key/value pairs
     *                      name is required
     *                      description is optional
     * @return Backup
     */
    public function createBackup($params = array())
    {
        if (!isset($params['instanceId'])) {
            $params['instanceId'] = $this->id;
        }

        $backup = new Backup($this->getService(), $params);
        $backup->create($params);
        return $backup;
    }

    public function populate($info, $setObjects = true)
    {
        parent::populate($info, $setObjects);

        if (is_object($info)) {
            $info = (array) $info;
        }

        if (isset($info['restorePoint']['backupRef'])) {
            $this->backupRef = $info['restorePoint']['backupRef'];
        }
    }

    /**
     * Generates the JSON string for Create()
     *
     * @return \stdClass
     */
    protected function createJson()
    {
        if (empty($this->flavor) || !is_object($this->flavor)) {
            throw new Exceptions\InstanceFlavorError(
                Lang::translate('The `flavor` attribute is required and must be a Flavor object')
            );
        }

        if (!isset($this->name)) {
            throw new Exceptions\InstanceError(
                Lang::translate('Instance name is required')
            );
        }

        $out = [
            'instance' => [
                    'flavorRef' => $this->flavor->links[0]->href,
                    'name'      => $this->name,
                    'volume'    => $this->volume
                ]
        ];

        if (isset($this->backupRef)) {
            $out['instance']['restorePoint'] = [
                'backupRef' => $this->backupRef
            ];
        }

        return (object) $out;
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
     * Generates the JSON object for Restart
     */
    private function restartJson()
    {
        return (object) array('restart' => new \stdClass);
    }

    /**
     * Generates the JSON object for Resize
     */
    private function resizeJson($flavorRef)
    {
        return (object) array(
            'resize' => (object) array('flavorRef' => $flavorRef)
        );
    }

    /**
     * Generates the JSON object for ResizeVolume
     */
    private function resizeVolumeJson($size)
    {
        return (object) array(
            'resize' => (object) array(
                    'volume' => (object) array('size' => $size)
                )
        );
    }
}
