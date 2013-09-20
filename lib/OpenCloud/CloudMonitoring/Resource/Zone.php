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
    private $id;
	private $country_code;
	private $label;
	private $source_ips;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'monitoring_zones';

    public function traceroute(array $options)
    {
        if (!$this->getId()) {
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
            $this->url('traceroute'), 
            'POST', 
            json_encode($params)
        );
    }
    
}