<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * a single node event, usually called as part of a Collection
 *
 * This is a read-only subresource.
 */
class NodeEvent extends Readonly {
	public
		$detailedMessage,
		$nodeId,
		$id,
		$type,
		$description,
		$category,
		$severity,
		$relativeUri,
		$accountId,
		$loadbalancerId,
		$title,
		$author,
		$created;
	protected static
		$json_name = 'nodeServiceEvent',
		$url_resource = 'nodes/events';
}