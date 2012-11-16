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
	 * This method creates a Node object and adds it to a list of Nodes
	 * to be added to the LoadBalancer. *Very important:* this method *NEVER*
	 * adds the nodes directly to the load balancer itself; it stores them
	 * on the object, and the nodes are added later, in one of two ways:
	 *
	 * * for a new LoadBalancer, the Nodes are added as part of the Create()
	 *   method call. 
	 * * for an existing LoadBalancer, you must call the AddNodes() method
	 *
	 * @api
	 * @param string $addr the IP address of the node
	 * @param integer $port the port # of the node
	 * @param boolean $condition the initial condition of the node
	 * @param string $type either PRIMARY or SECONDARY
	 * @param integer $weight the node weight (for round-robin)
	 * @throws \OpenCloud\DomainError if value is not valid
	 * @return void
	 */
	public function AddNode($addr, $port, $condition='ENABLED', 
			$type=NULL, $weight=NULL) {
	    $node = new Node($this);
	    $node->address = $addr;
	    $node->port = $port;
	    $cond = strtoupper($condition);
	    switch($cond) {
	    case 'ENABLED':
	    case 'DISABLED':
	    case 'DRAINING':
            $node->condition = $cond;
            break;
        default:
            throw new \OpenCloud\DomainError(sprintf(
                _('Value [%s] for Node::condition is not valid'), $condition));
	    }
	    if (isset($type)) {
	    	switch(strtoupper($type)) {
	    	case 'PRIMARY':
	    	case 'SECONDARY':
	    		$node->type = $type;
	    		break;
	    	default:
	    		throw new \OpenCloud\DomainError(sprintf(
	    			_('Value [%s] for Node::type is not valid'), $type));
	    	}
	    }
	    if (isset($weight)) {
	    	if (is_integer($weight))
	    		$node->weight = $weight;
	    	else
	    		throw new \OpenCloud\DomainError(sprintf(
	    			_('Value [%s] for Node::weight must be integer'), $weight));
	    }
	    $this->nodes[] = $node;
	}
	
	/**
	 * adds queued nodes to the load balancer
	 *
	 * In many cases, Nodes will be added to the Load Balancer when it is
	 * created (via the `Create()` method), but this method is provided when
	 * a set of Nodes needs to be added after the fact. 
	 *
	 * @api
	 * @return HttpResponse
	 */
	public function AddNodes() {
		if (count($this->nodes) < 1)
			throw new MissingValueError(
				_('Cannot add nodes; no nodes are defined'));
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
	
	/********** FACTORY METHODS **********
	 *
	 * These are used for the various sub-resources of LoadBalancer that are
	 * each managed by independent HTTP requests, such as the .../errorpage
	 * and the .../sessionpersistence objects.
	 */
	
	/**
	 */
	public function SessionPersistence() {
		return new LbSessionPersistence($this);
	}
	
	/**
	 * returns the load balancer's error page object
	 *
	 * @api
	 * @return LbErrorPage
	 */
	public function ErrorPage() {
		return new LbErrorPage($this);
	}
	
	/**
	 * returns statistics on the load balancer operation
	 *
	 * cannot be created, updated, or deleted
	 *
	 * @api
	 * @return LbStats
	 */
	public function Stats() {
		return new LbStats($this);
	}
	
	/**
	 */
	public function ConnectionThrottle() {
		return new ConnectionThrottle($this);
	}
	
	/**
	 */
	public function ConnectionLogging() {
		return new ConnectionLogging($this);
	}

	/**
	 */
	public function ContentCaching() {
		return new ContentCaching($this);
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
