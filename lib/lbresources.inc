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

class SubResource extends \OpenCloud\PersistentObject {
	private
		$_lb;	// holds the parent load balancer
	public function __construct(LoadBalancer $lb, $value=NULL) {
		$this->_lb = $lb;
		parent::__construct($lb->Service(), $value);
		/**
		 * Note that, since these sub-resources do not have an ID, we must
		 * fake out the `Refresh` method.
		 */
		 if (isset($this->id))
		 	$this->Refresh();
		 else
			 $this->Refresh('<no id>');
	}
	public function Url($subresource=NULL, $qstr=array()) {
		return $this->_lb->Url($this->ResourceName());
	}
	protected function CreateJson() {
    	$obj = new \stdClass();
    	$top = $this->JsonName();
    	if ($top) {
			$obj->$top = new \stdClass();
			foreach($this->_create_keys as $item)
				$obj->$top->$item = $this->$item;
		}
		else {
			foreach($this->_create_keys as $item)
				$obj->$item = $this->$item;
		}
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
} // end SubResource

/**
 * The /loadbalancer/{id}/errorpage manages the error page for the load
 * balancer.
 */
class ErrorPage extends SubResource {
	public
		$content;
	protected static
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
} // end ErrorPage

/**
 * Stats returns statistics about the load balancer
 */
class Stats extends SubResource {
	public
		$connectTimeOut,
		$connectError,
		$connectFailure,
		$dataTimedOut,
		$keepAliveTimedOut,
		$maxConn;
	protected static
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
 * sub-resource to manage allowed domains
 *
 * Note that this is actually a sub-resource of the load balancers service,
 * and not of the load balancer object. It's included here for convenience,
 * since it matches the pattern of the other LB subresources.
 *
 * @api
 */
class AllowedDomain extends \OpenCloud\PersistentObject {
	public
		$name;
	protected static
		$json_name = 'allowedDomain',
		$json_collection_name = 'allowedDomains',
		$json_collection_element = 'allowedDomain',
		$url_resource = 'loadbalancers/alloweddomains';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * VirtualIp represents a single virtual IP (usually returned in a Collection)
 *
 * Virtual IPs can be added to a load balancer when it is created; however,
 * this subresource allows the user to add or update them one at a time.
 *
 * @api
 */
class VirtualIp extends SubResource {
	public
		$id,
		$address,
		$type,
		$ipVersion;
	protected static
		$json_collection_name = 'virtualIps',
		$json_name = FALSE,
		$url_resource = 'virtualips';
	protected
		$_create_keys = array('type', 'ipVersion');
	public function Update($params=array()) { $this->NoUpdate(); }
}

/**
 * used to get a list of billable load balancers for a specific date range
 */
class BillableLoadBalancer extends SubResource {
	protected static
		$json_name = 'loadBalancer',
		$url_resource = 'billable';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
} // end BillableLoadBalancer

/**
 * used to get usage data for a load balancer
 */
class Usage extends SubResource {
	public
		$id,
		$averageNumConnections,
		$incomingTransfer,
		$outgoingTransfer,
		$averageNumConnectionsSsl,
		$incomingTransferSsl,
		$outgoingTransferSsl,
		$numVips,
		$numPolls,
		$startTime,
		$endTime,
		$vipType,
		$sslMode,
		$eventType;
	protected static
		$json_name = 'loadBalancerUsageRecord',
		$url_resource = 'usage';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
} // end Usage

/**
 * sub-resource to manage access lists
 *
 * @api
 */
class Access extends SubResource {
	public
		$id,
		$type,
		$address;
    protected static
    	$json_name = "accessList",
    	$url_resource = "accesslist";
    protected
    	$_create_keys = array('type', 'address');
	public function Update($params=array()) { $this->NoUpdate(); }
}

/**
 * sub-resource to manage health monitor
 */
class HealthMonitor extends SubResource {
	public
		$type;
	protected static
		$json_name = 'healthMonitor',
		$url_resource = 'healthmonitor';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
} // end HealthMonitor

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
	public function Create($parm=array()) { $this->Update($parm); }
}

/**
 * sub-resource to manage connection logging
 *
 * @api
 */
class ConnectionLogging extends SubResource {
	public
		$enabled;
    protected static
    	$json_name = "connectionLogging",
    	$url_resource = "connectionlogging";
    protected
    	$_create_keys = array( 'enabled' );
	public function Create($params=array()) { $this->Update($params); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * sub-resource to manage content caching
 *
 * @api
 */
class ContentCaching extends SubResource {
	public
		$enabled;
    protected static
    	$json_name = "contentCaching",
    	$url_resource = "contentcaching";
    protected
    	$_create_keys = array( 'enabled' );
	public function Create($parm=array()) { $this->Update($parm); }
	public function Delete() { $this->NoDelete(); }
}

/**
 * sub-resource to manage session persistence setting
 */
class LbSessionPersistence extends SubResource {
	public
		$persistenceType;
	protected static
		$json_name = 'sessionPersistence',
		$url_resource = 'sessionpersistence';
	private
		$_create_keys = array('persistenceType');
}
