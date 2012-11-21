<?php
/**
 * Defines a a number of classes that are child resources of LoadBalancer
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\LoadBalancerService;

require_once('persistentobject.inc');

class LbSubResource extends \OpenCloud\PersistentObject {
	private
		$_lb;	// holds the parent load balancer
	public function __construct(LoadBalancer $lb) {
		$this->_lb = $lb;
		parent::__construct($lb->Service());
		/**
		 * Note that, since these sub-resources do not have an ID, we must
		 * fake out the `Refresh` method. 
		 */
		 $this->Refresh('<no id>');
	}
	public function Url($subresource=NULL, $qstr=array()) { 
		return $this->_lb->Url($this->ResourceName());
	}
	protected function CreateJson() {
    	$obj = new \stdClass();
    	$top = $this->JsonName();
    	$obj->$top = new \stdClass();
    	foreach($this->_create_keys as $item)
			$obj->$top->$item = $this->$item;
    	return $obj;
	}
	protected function UpdateJson($params = array()) {
		return $this->CreateJson();
	}
	public function Parent() {
		return $this->_lb;
	}
	public function Name() {
		return sprintf(_('%s-%s'), 
			get_class($this), $this->Parent()->Id());
	}
} // end LbSubResource

/**
 * The /loadbalancer/{id}/errorpage manages the error page for the load
 * balancer.
 */
class LbErrorPage extends LbSubResource {
	public
		$content;
	public static
		$json_name = 'errorpage',
		$url_resource = 'errorpage';
	protected
		$_create_keys = array('content');
	/**
	 * creates a new error page
	 *
	 * This calls the Update() method, since it requires a PUT to create
	 * a new error page. A POST request is not supported, since the URL 
	 * resource is already defined.
	 *
	 * @param array $parm array of parameters
	 */
	public function Create($parm=array()) { $this->Update($parm); }
} // end LbErrorPage

/**
 * LbStats returns statistics about the load balancer
 */
class LbStats extends LbSubResource {
	public
		$connectTimeOut,
		$connectError,
		$connectFailure,
		$dataTimedOut,
		$keepAliveTimedOut,
		$maxConn;
	public static
		$json_name = FALSE,
		$url_resource = 'stats';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * information on a single node in the load balancer
 *
 * This extends `PersistentObject` because it has an ID, unlike the other
 * sub-resources.
 */
class Node extends \OpenCloud\PersistentObject {
	public
		$id,
		$address,
		$port,
		$condition,
		$status,
		$weight,
		$type;
	protected static
		$json_name = FALSE,
		$json_collection_name = 'nodes',
		$url_resource = 'nodes';
	private
		$_create_keys = array(
			'address',
			'port',
			'condition',
			'type',
			'weight'
		),
		$_lb;
	public function __construct(LoadBalancer $lb, $info=NULL) {
		$this->_lb = $lb;
		parent::__construct($lb->Service(), $info);
	}
	public function Parent() {
		return $this->_lb;
	}
}

/**
 * sub-resource to manage connection throttling
 *
 * @api
 */
class ConnectionThrottle extends LbSubResource {
	public
		$minConnections,
		$maxConnections,
		$maxConnectionRate,
		$rateInterval;
    public static
    	$json_name = "connectionThrottle",
    	$url_resource = "connectionthrottle";
    protected
    	$_create_keys = array(
    		'minConnections',
    		'maxConnections',
    		'maxConnectionRate',
    		'rateInterval'
    	);
	public function Create($parm=array()) { $this->Update($parm); }
}

/**
 * sub-resource to manage connection logging
 *
 * @api
 */
class ConnectionLogging extends LbSubResource {
	public
		$enabled;
    public static
    	$json_name = "connectionLogging",
    	$url_resource = "connectionlogging";
    protected
    	$_create_keys = array( 'enabled' );
	public function Create($parm=array()) { $this->Update($parm); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * sub-resource to manage content caching
 *
 * @api
 */
class ContentCaching extends LbSubResource {
	public
		$enabled;
    public static
    	$json_name = "contentCaching",
    	$url_resource = "contentcaching";
    protected
    	$_create_keys = array( 'enabled' );
	public function Create($parm=array()) { $this->Update($parm); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * sub-resource to manage allowable domains
*/
class LbAllowableDomains extends LbSubResource {
	public static
		$json_name = 'allowedDomain',
		$url_resource = 'alloweddomains';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * sub-resource to manage session persistence setting
 */
class LbSessionPersistence extends LbSubResource {
	public
		$persistenceType;
	protected static
		$json_name = 'sessionPersistence',
		$url_resource = 'sessionpersistence';
	private
		$_create_keys = array('persistenceType');
}