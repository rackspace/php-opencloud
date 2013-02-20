<?php
/**
 * Defines a DNS list of changes
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

/**
 * The Changes class represents the changes for a specific domain
 * since the requested date/time. If no date or time is specified, then
 * the system default is used (currently the prior day at midnight).
 * See
 * @link http://docs.rackspace.com/cdns/api/v1.0/cdns-devguide/content/List_Domain_Changes.html
 * for details on how the response is structured.
 *
 * This is also used for PTR records.
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Changes extends DnsObject {

	const
		DEFAULTSINCE = 'NA';

	public
		$since=self::DEFAULTSINCE,
		$to,
		$from,
		$totalEntries,
		$changes=array();

	protected static
		$json_name = FALSE,
		$json_collection_name = 'records',
		$url_resource = 'records';

	private
		$domain;

	/**
	 * constructor - save the parent domain
	 *
	 * NOTE: we're using -1 as a semaphore for the `$since` value; if
	 * $since==-1, then we leave it off the URL and get the default
	 * $since value (midnight the prior day local time).
	 *
	 * @param Domain $domain the parent domain
	 * @param string $since the date/time for start of change records
	 * @return void
	 */
	public function __construct(Domain $domain, $since=self::DEFAULTSINCE) {
		$this->domain = $domain;
		$this->since = $since;
		parent::__construct($domain->Service());
		$this->Refresh();
	}

	/**
	 * generates the URL for the request
	 */
	public function Url() {
		if ($this->since != self::DEFAULTSINCE)
			return $this->Parent()->Url('changes',
						array('since', $this->since));
		else
			return $this->Parent()->Url('changes');
	}

	/**
	 * no Create
	 */
	public function Create($params=array()) { $this->NoCreate(); }

	/**
	 * no Update
	 */
	public function Update($params=array()) { $this->NoUpdate(); }

	/**
	 * no Delete
	 */
	public function Delete() { $this->NoDelete(); }

	/**
	 * the parent is the domain
	 */
	public function Parent() {
		return $this->domain;
	}

	/**
	 * The "primary key field" is the $since parameter
	 */
	public function PrimaryKeyField() {
		return 'since';
	}

} // class DomainChanges