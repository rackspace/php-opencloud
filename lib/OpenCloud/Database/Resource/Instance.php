<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Database\Resource;

use OpenCloud\Common\Collection;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Compute\Resource\Flavor;
use OpenCloud\Database\Service;

/**
 * Instance represents an instance of DbService, similar to a Server in a
 * Compute service
 */
class Instance extends PersistentObject 
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
    
    protected static $json_name = 'instance';
    protected static $url_resource = 'instances';

    private $_databases;    // used to Create databases simultaneously
    private $_users;        // used to Create users simultaneously

    /**
     * Creates a new instance object
     *
     * This could use the default constructor, but we want to make sure that
     * the volume attribute is an object.
     *
     * @param \OpenCloud\DbService $service the DbService object associated 
     *      with this
     * @param mixed $info the ID or array of info for the object
     */
    public function __construct(Service $service, $info = null) 
    {
        $this->volume = new \stdClass;
        return parent::__construct($service, $info);
    }

    /**
     * Updates a database instance (not permitted)
     *
     * Update() is not supported by database instances; thus, this always
     * throws an exception.
     *
     * @throws InstanceUpdateError always
     */
    public function update($params = array()) 
    {
        return $this->noUpdate();
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
        return $this->action($this->resizeJson($flavor));
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
        $response = $this->getService()->request($this->url('root'), 'POST');

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 202) {
            throw new Exceptions\InstanceError(sprintf(
                Lang::translate('Error enabling root user for instance [%s], status [%d] response [%s]'),
                $this->name, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

        $object = json_decode($response->HttpBody());
        
        $this->checkJsonError();

        return (!empty($object->user)) ? new User($this, $object->user) : false;
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
        $response = $this->getService()->Request($this->Url('root'), 'GET');

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 202) {
            throw new Exceptions\InstanceError(sprintf(
                Lang::translate('Error enabling root user for instance [%s], status [%d] response [%s]'),
                $this->name, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
        
        $object = json_decode($response->httpBody());

        $this->checkJsonError();

        return !empty($object->rootEnabled);
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
     * @param string $name the user name
     * @param array $databases a simple array of database names
     * @return User
     */
    public function user($name = '', $databases = array()) 
    {
        return new User($this, $name, $databases);
    }

    /**
     * Returns a Collection of all databases in the instance
     *
     * @return Collection
     * @throws DatabaseListError if HTTP status is not Success
     */
    public function databaseList() 
    {
        $response = $this->getService()->request($this->Url('databases'));

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 200) {
            throw new Exceptions\DatabaseListError(sprintf(
                Lang::translate('Error listing databases for instance [%s], status [%d] response [%s]'),
                $this->name, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
        
        $object = json_decode($response->httpBody());

        $this->checkJsonError();

        $data = (!empty($object->databases)) ? $object->databases : array();
        return new Collection($this, 'OpenCloud\DbService\Database', $data);
    }

    /**
     * Returns a Collection of all users in the instance
     *
     * @return Collection
     * @throws UserListError if HTTP status is not Success
     */
    public function userList() 
    {
        $response = $this->getService()->Request($this->Url('users'));

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 200) {
            throw new Exceptions\UserListError(sprintf(
                Lang::translate('Error listing users for instance [%s], status [%d] response [%s]'),
                $this->name, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
        
        $object = json_decode($response->HttpBody());
        
        $this->checkJsonError();

        $data = (!empty($object->users)) ? $object->users : array();
        return new Collection($this, 'OpenCloud\DbService\User', $data);
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
        
        return (object) array(
            'instance' => (object) array(
                'flavorRef' => $this->flavor->links[0]->href,
                'name'      => $this->name,
                'volume'    => $this->volume
            )
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
