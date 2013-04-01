<?php

namespace OpenCloud\LoadBalancer;

/**
 * sub-resource to manage protocols (read-only)
 */
class Protocol extends \OpenCloud\AbstractClass\PersistentObject {
	public
		$name,
		$port;
	protected static
		$json_name = 'protocol',
		$url_resource = 'loadbalancers/protocols';
	public function Create($params=array()) { $this->NoCreate(); }
	public function Update($params=array()) { $this->NoUpdate(); }
	public function Delete() { $this->NoDelete(); }
}
