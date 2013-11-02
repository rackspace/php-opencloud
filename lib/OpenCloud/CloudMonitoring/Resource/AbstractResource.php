<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\PersistentObject;

abstract class AbstractResource extends PersistentObject
{
    protected function createJson()
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
                    "%s is required to create a new %s", $requiredKey, get_class()
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
     * @param bool $debug (default: false)
     * @return void
     */
    public function test($params = array(), $debug = false)
    {
        if (!empty($params)) {
            $this->populate($params);
        }

        $json = json_encode($this->createJson());
        $this->checkJsonError();
        
        // send the request
        return $this->getService()
            ->getClient()
            ->post($this->testUrl($debug), array(), $json)
            ->send()
            ->getDecodedBody();
    }

    /**
     * Test the validity of an existing resource.
     * 
     * @access public
     * @param bool $debug (default: false)
     * @return void
     */
    public function testExisting($debug = false)
    {
        $json = json_encode($this->updateJson());
        $this->checkJsonError();

        return $this->getClient()
            ->post($this->testUrl($debug), array(), $json)
            ->send()
            ->getDecodedBody();
    }
   
}