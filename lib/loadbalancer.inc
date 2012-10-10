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

require_once('persistentobject.inc');
require_once('metadata.inc');

/**
 * The LoadBalancer class represents a single load balancer
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class LoadBalancer extends \OpenCloud\PersistentObject {

	const
		JSON_NAME = 'loadBalancer',
		URL_RESOURCE = 'loadbalancers';

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
	 * @param string $addr
	 * @param integer $port
	 * @param boolean $enabled
	 * @return void
	 */
	public function AddNode($addr, $port, $enabled=TRUE) {
	    $obj = new \stdClass();
	    $obj->address = $addr;
	    $obj->port = $port;
	    $obj->enabled = $enabled;
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
	 * @return void
	 */
	public function AddVirtualIp($id='public') {
        $obj = new \stdClass();

        /**
         * check for PUBLIC or SERVICENET
         */
	    switch(strtoupper($id)) {
	    case 'PUBLIC':
	    case 'SERVICENET':
	        $obj->type = $id;
	        break;
	    default:
	        $obj->id = $id;
	    }

	    $this->virtualIps[] = $obj;
	}

	/********** PROTECTED METHODS **********/

	/**
	 * returns the JSON object for Create()
	 *
	 * @return stdClass
	 */
	protected function CreateJson() {
	    $obj = new \stdClass();
	}

	/**
	 * Returns the top-level document identifier
	 */
	protected function JsonName() {
		return self::JSON_NAME;
	}

	/**
	 * Returns the URL resource
	 */
	protected function ResourceName() {
		return self::URL_RESOURCE;
	}

}
