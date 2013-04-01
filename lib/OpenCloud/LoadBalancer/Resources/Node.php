<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * information on a single node in the load balancer
 *
 * This extends `PersistentObject` because it has an ID, unlike most other
 * sub-resources.
 */
class Node extends \OpenCloud\AbstractClass\PersistentObject {
	public
		$id,
		$address,
		$port,
		$condition,
		$status,
		$weight,
		$type;
	protected static
		$json_name = FALSE,
		$json_collection_name = 'nodes',
		$url_resource = 'nodes';
	private
		$_create_keys = array(
			'address',
			'port',
			'condition',
			'type',
			'weight'
		),
		$_lb;
	/**
	 * builds a new Node object
	 *
	 * @param LoadBalancer $lb the parent LB object
	 * @param mixed $info either an ID or an array of values
	 * @returns void
	 */
	public function __construct(\OpenCloud\LoadBalancer\LoadBalancer $lb, $info=NULL) {
		$this->_lb = $lb;
		parent::__construct($lb->Service(), $info);
	}
	/**
	 * returns the parent LoadBalancer object
	 *
	 * @return LoadBalancer
	 */
	public function Parent() {
		return $this->_lb;
	}
	/**
	 * returns the Node name
	 *
	 * @return string
	 */
	public function Name() {
		return get_class().'['.$this->Id().']';
	}
	/**
	 * returns the object for the Create JSON
	 *
	 * @return \stdClass
	 */
	protected function CreateJson() {
		$obj = new \stdClass();
		$obj->nodes = array();
		$node = new \stdClass();
		$node->node = new \stdClass();
		foreach($this->_create_keys as $key) {
			$node->node->$key = $this->$key;
		}
		$obj->nodes[] = $node;
		return $obj;
	}

	/**
	 * factory method to create a new Metadata child of the Node
	 *
	 * @api
	 * @return Metadata
	 */
	public function Metadata($data=NULL) {
		return new Metadata($this, $data);
	}

	/**
	 * factory method to create a Collection of Metadata object
	 *
	 * Note that these are metadata children of the Node, not of the
	 * LoadBalancer.
	 *
	 * @api
	 * @return Collection of Metadata
	 */
	public function MetadataList() {
		return $this->Service()->Collection(
			'\OpenCloud\LoadBalancer\Resources\Metadata', NULL, $this);
	}
}