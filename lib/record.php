<?php
/**
 * Defines a DNS record
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\DNS;

require_once(__DIR__.'/persistentobject.php');

/**
 * The Record class represents a single domain record
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Record extends \OpenCloud\PersistentObject {

	public
		$ttl,
		$updated,
		$created,
		$name,
		$id,
		$type,
		$data,
		$priority,
		$comment;
	
	protected static
		$json_name = FALSE,
		$json_collection_name = 'records',
		$url_resource = 'records';

	private
		$_domain,
		$_update_keys = array('name','ttl','data','priority','comment'),
		$_create_keys = array('name','ttl','type','data','priority','comment');
		
	/**
	 * create a new record object
	 */
	public function __construct(Domain $domain, $info=NULL) {
		$this->_domain = $domain;
		parent::__construct($domain->Service(), $info);
	}
	
	/**
	 * returns the parent domain
	 *
	 * @return Domain
	 */
	public function Parent() {
		return $this->_domain;
	}
	
	/* ---------- PROTECTED METHODS ---------- */
	
	/**
	 * creates the JSON for create
	 *
	 * @return stdClass
	 */
	protected function CreateJson() {
		return $this->GetJson($this->_create_keys);
	}
	
	/**
	 * creates the JSON for update
	 *
	 * @return stdClass
	 */
	protected function UpdateJson() {
		return $this->GetJson($this->_update_keys);
	}
	
	/* ---------- PRIVATE METHODS ---------- */
	
	/**
	 * returns JSON based on $keys
	 *
	 * @param array $keys list of items to include
	 * @return stdClass
	 */
	private function GetJson($keys) {
		$obj = new \stdClass;
		foreach($keys as $item)
			if ($this->$item)
				$obj->$item = $this->$item;
		return $obj;
	}
	
} // class Record
