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
    public function url($subresource = '', $query = array())
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
    protected function createJson()
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
    protected function updateJson($params = array())
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
        return $this->getService()->collection(get_class($this), $this->Url());
    }

    public function updateUrl()
    {
        return $this->url($this->id);
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
        $obj = $this->updateJson($params);
        $json = json_encode($obj);

        $this->checkJsonError();

        $this->getLogger()->info('{class}::Update JSON [{json}]', array(
            'class' => get_class($this), 
            'json'  => $json
        ));

        // send the request
        $response = $this->Service()->Request(
            $this->updateUrl(), 'PUT', array(), $json
        );

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 204) {
            throw new Exceptions\UpdateError(sprintf(
                Lang::translate('Error updating [%s] with [%s], status [%d] response [%s]'), 
                get_class($this), 
                $json, 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

        return $response;
    }

    /**
     * Delete object.
     * 
     * @codeCoverageIgnore
     * @access public
     * @return void
     */
    public function delete()
    {
        $this->getLogger()->info('{class}::delete()', array('class' => get_class($this)));

        // send the request
        $response = $this->getService()->request($this->url($this->id), 'DELETE');

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() > 204) {
            throw new Exceptions\DeleteError(sprintf(
                Lang::translate('Error deleting [%s] [%s], status [%d] response [%s]'), 
                get_class(), 
                $this->Name(), 
                $response->HttpStatus(), 
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

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
    protected function request($url, $method = 'GET', array $headers = array(), $body = null)
    {
        $response = $this->getService()->request($url, $method, $headers, $body);
        return ($body = $response->HttpBody()) ? json_decode($body) : false;
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
        return $this->customAction($this->testUrl($debug), 'POST', $json);
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

        $url = $this->url($this->id . '/test' . ($debug ? '?debug=true' : ''));
        return $this->customAction($url, 'POST', $json);
    }

    public function refresh($id = null, $url = null)
    {
        if (!$url) {
            $url = $this->url($id);
        }
        
        parent::refresh($id, $url);
    }
   
}