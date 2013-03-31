<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * Stats returns statistics about the load balancer
 */
class Stats extends Readonly {
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
}