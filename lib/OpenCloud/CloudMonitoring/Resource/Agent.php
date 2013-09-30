<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;

/**
 * Agent class.
 */
class Agent extends ReadOnlyResource
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
    	$response = $this->getClient()->get($url)->send()->getDecodedBody();
    	return $this->getService()->resource('AgentConnection', $response);
	}
	
}