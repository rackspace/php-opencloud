<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;
use OpenCloud\Common\PersistentObject;

class Entity extends PersistentObject
{
	
	public $label;
	public $agent_id;
	public $ip_addresses;
	public $metadata;

	public $name;

    protected static $json_name = 'entities';
    protected static $url_resource = 'entities';

    private $emptyObject = array(
        'label'        => '',
        'agent_id'     => '',
        'ip_addresses' => array(),
        'metadata'     => array()
    );

    private $requiredKeys = array(
        'label'
    );

    public function Url()
    {
        return $this->Parent()->Url($this->ResourceName());
    }

    protected function CreateJson() 
    {
    	$object = new \stdClass;
        
        foreach ($this->emptyObject as $key => $val) {
            $object->$key = $this->$key;
        }

        foreach ($this->requiredKeys as $requiredKey) {
            if (!isset($object->$requiredKey)) {
                throw new Exception\EntityException(sprintf(
                    "%s is required to create a new %s",
                    $requiredKey,
                    get_class()
                ));
            }
        }

    	return $object;
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

        return $this->Parent()->Collection(get_class(), $url);
    }

}