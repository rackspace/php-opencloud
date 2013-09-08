<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;

/**
 * Zone class.
 * 
 * @extends ReadOnlyResource
 */
class Zone extends ReadOnlyResource implements ResourceInterface
{
	public $country_code;
	public $label;
	public $source_ips;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'monitoring_zones';

    public function baseUrl($subresource = '')
    {
    	return $this->getParent()->url($this->resourceName());
    }

    public function traceroute(array $options)
    {
        if (!$this->id) {
            throw new Exception\ZoneException(
                'Please specify a zone ID'
            );    
        }
        
        if (!isset($options['target'])) {
            throw new Exception\ZoneException(
                'Please specify a "target" value'
            );
        }
        
        $params = (object) array('target' => $options['target']);
        
        if (isset($options['target_resolver'])) {
            $params->target_resolver = $options['target_resolver'];
        }
        
        return $this->customAction(
            $this->url($this->id . '/traceroute'), 
            'POST', 
            json_encode($params)
        );
    }
    
}