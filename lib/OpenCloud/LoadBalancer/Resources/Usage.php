<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * used to get usage data for a load balancer
 */
class Usage extends Readonly {
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
} // end Usage