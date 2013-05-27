<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\CloudMonitoring\Exception;

class Check extends PersistentObject
{
    public $name;
    public $id;
    public $entity;

	public $type;
	public $details;
	public $disabled;
	public $label;
	public $metadata;
	public $period;
	public $timeout;

	public $monitoring_zones_poll;
	public $target_alias;
	public $target_hostname;
	public $target_resolver;

    protected static $json_name = 'checks';
    protected static $url_resource = 'checks';

    private $emptyObject = array(
        'type'     => '',
        'details'  => '',
        'disabled' => array(),
        'label'    => '',
        'metadata' => array(),
        'period'   => '',
        'timeout'  => '',
        'monitoring_zones_poll' => '',
        'target_alias'    => '',
        'target_hostname' => '',
        'target_resolver' => '',
    );

    private $requiredKeys = array('type');

    private $service;

    public function __construct($parentObject, $info = null) 
    {
        $this->service = $parentObject;
        parent::__construct($parentObject, $info = null);
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function Url($subresource = '')
    {
        return $this->Parent()->Url() . '/' . $this->Parent()->id . '/'. $this->ResourceName() . "/$subresource";
    }

    public function testUrl($debug = false)
    {
        $url = $this->Parent()->Url() . '/' . $this->Parent()->id . '/test-check'; 
        return ($debug !== true) ? $url : $url . '?debug=true';
    }

    public function Service()
    {
        return $this->service;
    }

    public function Parent()
    {
        return $this->entity;
    }

    protected function CreateJson() 
    {
    	$object = new \stdClass;
        
        foreach ($this->emptyObject as $key => $val) {
            $object->$key = $this->$key;
        }

        foreach ($this->requiredKeys as $requiredKey) {
            if (!isset($object->$requiredKey)) {
                throw new Exception\CheckException(sprintf(
                    "%s is required to create a new %s",
                    $requiredKey,
                    get_class()
                ));
            }
        }

    	return $object;
    }

    protected function UpdateJson() 
    {
        foreach ($this->requiredKeys as $requiredKey) {
            if (!isset($this->$requiredKey)) {
                throw new Exception\CheckException(sprintf(
                    "%s is required to create a new %s",
                    $requiredKey,
                    get_class()
                ));
            }
        }

        return $this;
    }  

    public function listAll($details = true, array $filter = array())
    {
        if (!is_bool($details)) {
            throw new Exceptions\InvalidArgumentException(
            	Lang::translate('First argument for Compute::ServerList() must be boolean'
            ));
        }

        if (!is_array($filter)) {
            throw new Exceptions\InvalidArgumentException(
            	Lang::translate('Second argument for Compute::ServerList() must be array'
            ));
        }

        $url = $this->Url(self::ResourceName(), $filter);

        return $this->Service()->Collection(get_class(), $url);
    }

    public function test($debug = false)
    {
        $obj = $this->UpdateJson();
        $json = json_encode($obj);

        if ($this->CheckJsonError()) {
            return false;
        }

        // send the request
        $response = $this->Service()->Request(
            $this->testUrl($debug), 
            'POST', 
            array(), 
            $json
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

    public function testExisting($debug = false)
    {
        $obj = $this->UpdateJson();
        $json = json_encode($obj);

        if ($this->CheckJsonError()) {
            return false;
        }

        $url = $this->Url($this->id . '/test');
        if ($debug) {
            $url .= '?debug=true';
        }

        // send the request
        $response = $this->Service()->Request(
            $this->Url($url), 
            'POST', 
            array(), 
            $json
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

    public function get($id)
    {
        $primaryKey = $this->PrimaryKeyField();

        if ($id === null) {
            if (!$id = $this->$primaryKey) {
                throw new Exception\CheckException(sprintf(
                    Lang::translate('%s has no ID; cannot be refreshed'), 
                    get_class()
                ));
            }
        }

        // retrieve it
        $this->id = $id;

        // reset status, if available
        if (property_exists($this, 'status')) {
            $this->status = null;
        }

        // perform a GET on the URL
        $response = $this->Service()->Request($this->Url());

        // check status codes
        if ($response->HttpStatus() == 404) {
            throw new Exceptions\InstanceNotFound(
                sprintf(Lang::translate('%s [%s] not found [%s]'),
                get_class($this), 
                $this->$primaryKey, 
                $this->Url()
            ));
        }

        if ($response->HttpStatus() >= 300) {
            throw new Exceptions\UnknownError(
                sprintf(Lang::translate('Unexpected %s error [%d] [%s]'),
                get_class($this),
                $response->HttpStatus(),
                $response->HttpBody()
            ));
        }

        // check for empty response
        if (!$response->HttpBody()) {
            throw new Exceptions\EmptyResponseError(
                sprintf(Lang::translate('%s::Refresh() unexpected empty response, URL [%s]'),
                get_class($this),
                $this->Url()
            ));
        }

        // we're ok, reload the response
        if ($json = $response->HttpBody()) {
            $this->debug('Refresh() JSON [%s]', $json);
            $resp = json_decode($json);

                foreach($resp->values[0] as $item => $value) {
                    $this->$item = $value;
                }
            
        }
    }

}