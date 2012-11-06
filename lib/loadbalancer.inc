<?php
/**
 * Defines a load balancer object
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\LoadBalancerService;

require_once('persistentobject.inc');	// handles persistence
require_once('metadata.inc');			// metadata common
require_once('lbresources.inc');		// child resources

/**
 * The LoadBalancer class represents a single load balancer
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class LoadBalancer extends \OpenCloud\PersistentObject {

	public
		$id,
		$name,
		$port,
		$protocol,
		$virtualIps=array(),
		$nodes=array(),
		$accessList,
		$algorithm,
		$connectionLogging,
		$connectionThrottle,
		$healthMonitor,
		$sessionPersistence,
		$metadata = array(),
		/* returned in response */
		$created,
		$updated,
		$sourceAddresses;
	
	protected static 
		$json_name = 'loadBalancer',
		$url_resource = 'loadbalancers';

	private
	    $_create_keys = array(
	        'name',
	        'port',
	        'protocol',
	        'virtualIps',
	        'nodes',
	        'accessList',
	        'algorithm',
	        'connectionLogging',
	        'connectionThrottle',
	        'healthMonitor',
	        'sessionPersistence'
	    );

	/**
	 * adds a node to the load balancer
	 *
	 * @api
	 * @param string $addr the IP address of the node
	 * @param integer $port the port # of the node
	 * @param boolean $condition the initial condition of the node
	 * @return void
	 */
	public function AddNode($addr, $port, $condition='ENABLED') {
	    $obj = new \stdClass();
	    $obj->address = $addr;
	    $obj->port = $port;
	    $cond = strtoupper($condition);
	    switch($cond) {
	    case 'ENABLED':
	    case 'DISABLED':
	    case 'DRAINING':
            $obj->condition = $cond;
            break;
        default:
            throw new \OpenCloud\DomainError(sprintf(
                _('Value [%s] for condition is not valid'), $condition));
	    }
	    $this->nodes[] = $obj;
	}

	/**
	 * adds a virtual IP to the load balancer
	 *
	 * You can use the strings `'PUBLIC'` or `'SERVICENET`' to indicate the
	 * public or internal networks, or you can pass the `Id` of an existing
	 * IP address.
	 *
	 * @api
	 * @param string $id either 'public' or 'servicenet' or an ID of an
	 *      existing IP address
	 * @param integer $ipVersion either null, 4, or 6 (both, IPv4, or IPv6)
	 * @return void
	 */
	public function AddVirtualIp($id='PUBLIC', $ipVersion=NULL) {
        $obj = new \stdClass();

        /**
         * check for PUBLIC or SERVICENET
         */
	    switch(strtoupper($id)) {
	    case 'PUBLIC':
	    case 'SERVICENET':
	        $obj->type = strtoupper($id);
	        break;
	    default:
	        $obj->id = $id;
	    }
	    
	    if ($ipVersion) {
	        switch($ipVersion) {
	        case 4:
	            $obj->version = 'IPV4';
	            break;
	        case 6:
	            $obj->version = 'IPV6';
	            break;
	        default:
	            throw new \OpenCloud\DomainError(sprintf(
	                _('Value [%s] for ipVersion is not vaid'), $ipVersion));
	        }
	    }

	    $this->virtualIps[] = $obj;
	}
	
	/**
	 */
	public function SessionPersistence() {
		return new LbSessionPersistence($this);
	}
	
	/**
	 * returns statistics on this LoadBalancer
	 *
	 * @api
	 * @return stdClass object containing LoadBalancer stats
	 * @throws OpenCloud\HttpError if HTTP return code is not 200
	 */
	public function Stats() {
		$url = $this->Url('stats');
		$response = $this->Service()->Request($url);
		
		// trap errors
		if ($response->HttpStatus() > 200)
			throw new \OpenCloud\HttpError(sprintf(
				_('HTTP error on Stats() method, code [%d] response [%s]'),
				$response->HttpStatus(), $response->HttpBody()));
		
		// parse the returned json
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;
		return $obj;
	}

	/********** PROTECTED METHODS **********/

	/**
	 * returns the JSON object for Create()
	 *
	 * @return stdClass
	 */
	protected function CreateJson() {
	    $obj = new \stdClass();
	    $elem = $this->JsonName();
	    $obj->$elem = new \stdClass();
	    
	    // set the properties
	    foreach($this->_create_keys as $key) {
	    	if ($this->$key) {
	    		$obj->$elem->$key = $this->$key;
	    	}
	    }
	    
	    return $obj;
	}

}
