<?php
/**
 * Defines a DNS domain
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\DNS;

require_once(__DIR__.'/dnsobject.php');
require_once(__DIR__.'/record.php');

/**
 * The Domain class represents a single domain
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Domain extends DnsObject {

	public
		$id,
		$accountId,
		$ttl,
		$updated,
		$emailAddress,
		$created,
		$name,
		$comment;
	
	protected static
		$json_name = FALSE,
		$json_collection_name = 'domains',
		$url_resource = 'domains';

	private
		$_create_keys = array('name','emailAddress','ttl','comment'),
		$_update_keys = array('emailAddress','ttl','comment');
	
	/**
	 * returns a Record object
	 *
	 * @return Record
	 */
	public function Record($info=NULL) {
		return new Record($this, $info);
	}
	
	/**
	 * returns a Collection of Record objects
	 *
	 * @param array $filter query-string parameters
	 * @return \OpenCloud\Collection
	 */
	public function RecordList($filter=array()) {
		$url = $this->Url(Record::ResourceName(), $filter);
		return $this->Parent()->Collection(
			'\OpenCloud\DNS\Record', NULL, $this);
	}
	
	/**
	 * returns a Collection of subdomains
	 *
	 * The subdomains are all `DNS:Domain` objects that are children of
	 * the current domain.
	 *
	 * @param array $filter key/value pairs for query string parameters
	 * return \OpenCloud\Collection
	 */
	public function SubdomainList($filter=array()) {
		return $this->Parent()->Collection(
			'\OpenCloud\DNS\Domain', NULL, $this);
	}
	
	/**
	 * exports the domain
	 *
	 * @return AsyncResponse
	 */
	public function Export() {
		$url = $this->Url('export');
		return $this->Service()->AsyncRequest($url);
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
	
} // class Domain