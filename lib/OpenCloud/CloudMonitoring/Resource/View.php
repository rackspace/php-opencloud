<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * View class.
 * 
 * @extends ReadOnlyResource
 */
class View extends ReadOnlyResource implements ResourceInterface
{
    public $timestamp;
    public $entity;
    public $alarms;
    public $checks;
    public $latest_alarm_states;
    
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'views/overview';
    
    protected $associatedResources = array(
        'entity' => 'Entity'
    );
    
    protected $associatedCollections = array(
        'alarms' => 'Alarm',
        'checks' => 'Check'
    );
    
    public function baseUrl()
    {
        return $this->Service()->Url($this->ResourceName());
    }
    
    public function setDataOnly($bool)
    {
        $this->dataOnly = $bool;   
    }
    
    public function getDataOnly()
    {
        return $this->dataOnly;
    }

}