<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\PersistentObject;

/**
 * Abstract AbstractResource class.
 * 
 * @abstract
 * @extends PersistentObject
 * @package phpOpenCloud
 * @version 1.0
 * @author  Jamie Hannaford <jamie@limetree.org>
 */
abstract class AbstractResource extends PersistentObject
{
    /**
     * __construct function.
     * 
     * @access public
     * @param mixed $service
     * @param mixed $info
     * @return void
     */
    public function __construct($service, $info)
    {
        $this->setService($service);
        parent::__construct($service, $info);
    }

    /**
     * Procedure for JSON create object.
     * 
     * @access protected
     * @return void
     */
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

    /**
     * Procedure for JSON update object.
     * 
     * @access protected
     * @return void
     */
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

        return $this->customAction($this->testUrl($debug), 'POST', $json);
    }
   
}