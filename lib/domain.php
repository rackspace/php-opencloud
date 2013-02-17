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

	protected
		$_create_keys = array('name','emailAddress','ttl','comment'),
		$_update_keys = array('emailAddress','ttl','comment');
	
	private
		$records = array(),
		$subdomains = array();
		
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
	 * returns a Subdomain object (child of current domain)
	 *
	 */
	public function Subdomain($info=array()) {
		return new Subdomain($this, $info);
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
			'\OpenCloud\DNS\Subdomain', NULL, $this);
	}
	
	/**
	 * Adds a new record to the list (for multiple record creation)
	 *
	 * @api
	 * @param Record $rec the record to add
	 * @return void
	 */
	public function AddRecord(Record $rec) {
		$this->records[] = $rec;
	}
	
	/**
	 * adds a new subdomain (for multiple subdomain creation)
	 *
	 * @api
	 * @param Subdomain $subd the subdomain to add
	 * @return void
	 */
	public function AddSubdomain(Subdomain $subd) {
		$this->subdomains[] = $subd;
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
	
	/**
	 * imports domain records
	 * @TODO
	 * Should this go at the DNS level? After all, we're importing a domain,
	 * and not importing it into a domain object. Or are we?
	 */
	public function Import() {
		$url = $this->Service()->Url('domains/import');
		return $this->Service()->AsyncRequest($url, 'POST', '');
	}
	
	/* ---------- PROTECTED METHODS ---------- */
	
	/**
	 * handles creation of multiple records at Create()
	 *
	 * @api
	 * @return \stdClass
	 */
	public function CreateJson() {
		$obj = parent::CreateJson();
		if (count($this->records) > 0) {
			$obj->domains[0]->recordsList = new \stdClass;
			$obj->domains[0]->recordsList->records = array();
			foreach($this->records as $rec) {
				$robj = new \stdClass;
				foreach($rec->CreateKeys() as $key)
					if (isset($rec->$key))
						$robj->$key = $rec->$key;
				$obj->domains[0]->recordsList->records[] = $robj;
			}
		}
		// TODO: add subdomains
		return $obj;
	}
	
} // class Domain

class Subdomain extends Domain {

	protected static
		$json_name = FALSE,
		$json_collection_name = 'domains',
		$url_resource = 'subdomains';
	
	private
		$_parent;
	public function __construct(Domain $parent, $info=array()) {
		$this->_parent = $parent;
		return parent::__construct($parent->Service(), $info);
	}
	public function Parent() {
		return $this->_parent;
	}

} // class Subdomain