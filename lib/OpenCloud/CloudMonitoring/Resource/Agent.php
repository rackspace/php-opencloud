<?php

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\CloudMonitoring\Exception;

/**
 * Agent class.
 * 
 * @extends ReadOnlyResource
 * @implements ResourceInterface
 */
class Agent extends ReadOnlyResource implements ResourceInterface
{
	private $id;
	private $last_connected;
	
    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'agents';

	public function getConnections()
	{
    	if (!$this->getId()) {
        	throw new Exception\AgentException(
        	   'Please specify an "ID" value'
        	);
    	}
    	
    	$url = $this->url('connections');
    	return $this->getService()->resourceList('AgentConnection', $url);
	}
	
	public function getConnection($connectionId)
	{
    	if (!$this->getId()) {
        	throw new Exception\AgentException(
        	   'Please specify an "ID" value'
        	);
    	}
    	
    	$url = $this->url('connections/' . $connectionId);
    	$response = $this->customAction($url);
    	return $this->getService()->resource('AgentConnection', $response);
	}
	
}