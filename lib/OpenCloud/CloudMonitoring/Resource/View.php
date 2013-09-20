<?php

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * View class.
 * 
 * @extends ReadOnlyResource
 */
class View extends ReadOnlyResource implements ResourceInterface
{
    private $timestamp;
    private $entity;
    private $alarms;
    private $checks;
    private $latest_alarm_states;
    
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
    
}