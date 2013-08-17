<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\PersistentObject;
use OpenCloud\CloudMonitoring\Exception;

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
     * Unique identifier
     * 
     * @var mixed
     * @access public
     */
    public $id;

    /**
     * Name
     * 
     * @var mixed
     * @access public
     */
    public $name;

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
     * Retrieve property from array/object.
     * 
     * @access public
     * @param mixed $haystack
     * @param mixed $needle
     * @return void
     */
    public function getProperty($haystack, $needle)
    {
        if (is_object($haystack) && isset($haystack->$needle)) {
            return $haystack->$needle;
        }

        if (is_array($haystack) && isset($haystack[$needle])) {
            return $haystack[$needle];
        }

        return false;
    }
    
    /**
     * Url function.
     * 
     * @access public
     * @param string $subresource (default: '')
     * @return void
     */
    public function Url($subresource = '', $query = array())
    {
        $url = $this->baseUrl();

        if ($subresource) {
            $url .= "/$subresource";
        }

        return $url . $this->MakeQueryString($query);
    }

    /**
     * Procedure for JSON create object.
     * 
     * @access protected
     * @return void
     */
    protected function CreateJson()
    {
        $object = new \stdClass;

        foreach (static::$emptyObject as $key) {
            if (isset($this->$key)) {
                $object->$key = $this->$key;
            }
        }

        foreach (static::$requiredKeys as $requiredKey) {
            if (!isset($object->$requiredKey)) {
                throw new Exceptions\CreateError(sprintf(
                    "%s is required to create a new %s", $requiredKey, get_class()
                ));
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
    protected function UpdateJson($params = array())
    {
        foreach (static::$requiredKeys as $requiredKey) {
            if (!isset($this->$requiredKey)) {
                throw new Exceptions\UpdateError(sprintf(
                    "%s is required to create a new %s", $requiredKey, get_class()
                ));
            }
        }

        return $this;
    }

    /**
     * Retrieves a collection of resource objects.
     * 
     * @access public
     * @return void
     */
    public function listAll()
    {
        return $this->Service()->Collection(get_class($this), $this->Url());
    }

    public function updateUrl()
    {
        return $this->Url($this->id);
    }

    /**
     * Update object.
     * 
     * @access public
     * @param array $params (default: array())
     * @return void
     */
    public function update($params = array())
    {
        // set parameters
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        // debug
        $this->getLogger()->info('{class}::update({name})', array(
            'class' => get_class($this), 
            'name'  => $this->name()
        ));

        // construct the JSON
        $obj = $this->UpdateJson($params);
        $json = json_encode($obj);

        if ($this->CheckJsonError()) {
            return false;
        }

        $this->getLogger()->info('{class}::Update JSON [{json}]', array(
            'class' => get_class($this), 
            'json'  => $json
        ));

        // send the request
        $response = $this->Service()->Request(
            $this->updateUrl(), 'PUT', array(), $json
        );

        // check the return code
        if ($response->HttpStatus() > 204) {
            throw new Exceptions\UpdateError(sprintf(
                Lang::translate('Error updating [%s] with [%s], status [%d] response [%s]'), 
                get_class($this), 
                $json, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }

        return $response;
    }

    /**
     * Delete object.
     * 
     * @access public
     * @return void
     */
    public function Delete()
    {
        $this->getLogger()->info('{class}::delete()', array('class' => get_class($this)));

        // send the request
        $response = $this->Service()->Request($this->Url($this->id), 'DELETE');

        // check the return code
        if ($response->HttpStatus() > 204) {
            throw new Exceptions\DeleteError(sprintf(
                Lang::translate('Error deleting [%s] [%s], status [%d] response [%s]'), 
                get_class(), 
                $this->Name(), 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }

        return $response;
    }

    /**
     * Request function.
     * 
     * @access protected
     * @param mixed $url
     * @param string $method (default: 'GET')
     * @param array $headers (default: array())
     * @param mixed $body (default: null)
     * @return void
     */
    protected function Request($url, $method = 'GET', array $headers = array(), $body = null)
    {
        $response = $this->Service()->Request($url, $method, $headers, $body);

        if ($body = $response->HttpBody()) {
            return json_decode($body);
        }

        return false;
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
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }

        $obj = $this->CreateJson();
        $json = json_encode($obj);

        if ($this->CheckJsonError()) {
            return false;
        }

        // send the request
        $response = $this->Service()->Request(
            $this->testUrl($debug), 'POST', array(), $json
        );

        // check the return code
        if ($response->HttpStatus() > 204) {
            throw new Exceptions\TestException(sprintf(
                Lang::translate('Error updating [%s] with [%s], status [%d] response [%s]'), 
                get_class($this), 
                $json, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }

        return $response;
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
        $obj = $this->UpdateJson();
        $json = json_encode($obj);

        if ($this->CheckJsonError()) {
            return false;
        }

        $url = $this->Url($this->id . '/test' . ($debug ? '?debug=true' : ''));

        // send the request
        $response = $this->Service()->Request(
            $url, 'POST', array(), $json
        );

        // check the return code
        if ($response->HttpStatus() > 204) {
            throw new Exception\TestException(sprintf(
                'Error testing [%s] with [%s], status [%d] response [%s]', 
                get_class($this), 
                $json, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }

        return $response;
    }

    public function refresh($id = null, $url = null)
    {
        if (!$url) {
            $url = $this->Url($id);
        }
        
        parent::refresh($id, $url);
    }
   

}