<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * sub-resource to read health monitor info
 */
class HealthMonitor extends ReadonlySubResource {
	public
		$type;
	protected static
		$json_name = 'healthMonitor',
		$url_resource = 'healthmonitor';
} // end HealthMonitor


