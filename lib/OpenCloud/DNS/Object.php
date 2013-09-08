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

namespace OpenCloud\DNS;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;

/**
 * The DnsObject class is an extension of the PersistentObject class that
 * permits the asynchronous responses used by Cloud DNS
 */
abstract class Object extends PersistentObject 
{

    /**
     * Create() returns an asynchronous response
     *
     * @param array $params array of key/value pairs
     * @return AsyncResponse
     */
    public function create($params = array()) 
    {
        return new AsyncResponse($this->Service(), parent::create($params)->httpBody());
    }

    /**
     * Update() returns an asynchronous response
     *
     * @param array $params array of key/value pairs
     * @return AsyncResponse
     */
    public function update($params = array()) 
    {
        return new AsyncResponse($this->Service(), parent::update($params)->httpBody());
    }

    /**
     * Delete() returns an asynchronous response
     *
     * @param array $params array of key/value pairs
     * @return AsyncResponse
     */
    public function delete() 
    {
        return new AsyncResponse($this->Service(), parent::delete()->httpBody());
    }

    /**
     * creates the JSON for create
     *
     * @return stdClass
     */
    protected function createJson() 
    {
        if (!$this->getCreateKeys()) {
            throw new Exceptions\CreateError(
                Lang::translate('Missing [createKeys]')
            );
        }

        $object = new \stdClass;
        $object->{self::jsonCollectionName()} = array(
            $this->getJson($this->getCreateKeys())
        );
        
        return $object;
    }

    /**
     * creates the JSON for update
     *
     * @return stdClass
     */
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
        foreach($keys as $item) {
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
