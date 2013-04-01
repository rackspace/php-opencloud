<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * sub-resource to manage Metadata
 */
class Metadata extends SubResource {
	public
		$id,
		$key,
		$value;
	protected static
		$json_name = 'meta',
		$json_collection_name = 'metadata',
		$url_resource = 'metadata';
	protected
		$_create_keys = array('key', 'value');
	public function Name() {
		return $this->key;
	}
}
