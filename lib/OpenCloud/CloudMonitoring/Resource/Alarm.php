<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\CloudMonitoring\Exception;

/**
 * Alarm class.
 * 
 * @extends AbstractResource
 */
class Alarm extends AbstractResource implements ResourceInterface
{
    private $id;
	private $check_id;
	private $notification_plan_id;
	private $criteria;
	private $disabled;
	private $label;
	protected $metadata;
	
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'alarms';
    
    protected static $requiredKeys = array(
        'check_id',
        'notification_plan_id'
    );
    
    protected static $emptyObject = array(
        'check_id',
        'notification_plan_id',
        'criteria',
        'disabled',
        'label',
        'metadata'
    );
    

    public function baseUrl()
    {
        return $this->getParent()->url() . '/' . $this->getParent()->getId() . '/' . $this->resourceName();
    }
    
    public function createUrl()
    {
        return $this->baseUrl();
    }
    
    public function test($params = array(), $debug = false)
    {
        if (!isset($params['criteria'])) {
            throw new Exception\AlarmException(
                'Please specify a "criteria" value'
            );
        }
        
        if (!isset($params['check_data']) || !is_array($params['check_data'])) {
            throw new Exception\AlarmException(
                'Please specify a "check data" array'
            );
        }
        
        $url  = $this->getParent()->url('test-alarm');
        $body = (object) $params;
        
        return $this->getClient()->post($url, array(), $body)->send();
    }	
	
}