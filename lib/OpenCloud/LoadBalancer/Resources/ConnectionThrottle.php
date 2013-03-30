<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * sub-resource to manage connection throttling
 *
 * @api
 */
class ConnectionThrottle extends SubResource {
	public
		$minConnections,
		$maxConnections,
		$maxConnectionRate,
		$rateInterval;
    protected static
    	$json_name = "connectionThrottle",
    	$url_resource = "connectionthrottle";
    protected
    	$_create_keys = array(
    		'minConnections',
    		'maxConnections',
    		'maxConnectionRate',
    		'rateInterval'
    	);
    /**
     * create uses PUT like Update
     */
	public function Create($parm=array()) { $this->Update($parm); }
}