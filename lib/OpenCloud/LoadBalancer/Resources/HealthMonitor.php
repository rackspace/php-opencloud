<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * sub-resource to manage health monitor info
 */
class HealthMonitor extends SubResource {
	public
		$type,
		$delay,
		$timeout,
		$attemptsBeforeDeactivation,
		$bodyRegex,
		$hostHeader,
		$path,
		$statusRegex;
	protected static
		$json_name = 'healthMonitor',
		$url_resource = 'healthmonitor';
	protected
		$_create_keys = array(
			'type',
			'delay',
			'timeout',
			'attemptsBeforeDeactivation',
			'bodyRegex',
			'hostHeader',
			'path',
			'statusRegex'
		);
	/**
	 * creates a new health monitor
	 *
	 * This calls the Update() method, since it requires a PUT to create
	 * a new error page. A POST request is not supported, since the URL
	 * resource is already defined.
	 *
	 * @param array $parm array of parameters
	 */
	public function Create($parm=array()) { $this->Update($parm); }
} // end HealthMonitor
