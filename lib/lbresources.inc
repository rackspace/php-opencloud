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
		$_lb;
	public function __construct(LoadBalancer $lb) {
		$this->_lb = $lb;
		return parent::__construct($lb->Service());
	}
	public function Url($subresource) { 
		return $_lb->Url($this->ResourceName());
	}
}

class LbAllowableDomains extends LbSubResource {
	public static
		$json_name = 'allowedDomain',
		$url_resource = 'alloweddomains';
	public function Create() { $this->NoCreate(); }
	public function Delete() { $this->NoDelete(); }
	public function Update() { $this->NoUpdate(); }
}

class LbSessionPersistence extends LbSubResource {
	public
		$persistenceType;
	protected static
		$json_name = 'sessionPersistence',
		$url_resource = 'sessionpersistence';
	private
		$_create_keys = array('persistenceType');
}