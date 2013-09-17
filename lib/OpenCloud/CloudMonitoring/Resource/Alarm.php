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
    public $id;
	public $check_id;
	public $notification_plan_id;
	public $criteria;
	public $disabled;
	public $label;
	public $metadata;
	
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
        $urls = $this->getParent()->Url();
        foreach($urls as &$url) {
            $url .= '/' . $this->getParent()->id . '/' . $this->resourceName();
        }
        return $urls;
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
        
        $urls = $this->getParent()->Url();
        foreach($urls as &$url) {
            $url .= '/' . $this->getParent()->id . '/test-alarm';
        }
        
        return $this->request($urls, 'POST', array(), json_encode((object) $params));
    }	
	
}