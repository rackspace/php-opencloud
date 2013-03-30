<?php

namespace OpenCloud\LoadBalancer\Resources;

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