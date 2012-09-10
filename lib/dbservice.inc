<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;

require_once('nova.inc');
require_once('instancelist.inc');
require_once('instance.inc');

/**
 * The Rackspace Database As A Service (aka "Red Dwarf")
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class DbService extends Nova {

	/**
	 * Creates a new DbService service connection
	 *
	 * This is not normally called directly, but via the factory method on the
	 * OpenStack or Rackspace connection object.
	 *
	 * @param OpenStack $conn the connection on which to create the service
	 * @param string $name the name of the service (e.g., "cloudDatabases")
	 * @param string $region the region of the service (e.g., "DFW" or "LON")
	 * @param string $urltype the type of URL (normally "publicURL")
	 */
	public function __construct(OpenStack $conn, $name, $region, $urltype) {
		parent::__construct($conn, 'rax:database', $name, $region, $urltype);
	}

	/**
	 * Returns the URL of this database service, or optionally that of
	 * an instance
	 */
	public function Url($resource='instances', $args=array()) {
		return parent::Url($resource, $args);
	}

	/**
	 * returns a list of flavors
	 *
	 * just call the parent FlavorList() method, but pass FALSE
	 * because the /flavors/detail resource is not supported
	 */
	public function FlavorList() {
	    return parent::FlavorList(FALSE);
	}

	/**
	 * Factory method to create a Instance object
	 */
	public function Instance($id=NULL) {
		return new DbService\Instance($this, $id);
	}

	/**
	 * Factory method to create a Collection of Instance objects
	 */
	public function InstanceList($params=array()) {
		$response = $this->Request($this->Url());
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;
		if (!is_object($obj))
			return new DbService\InstanceList($this, array());
		return new DbService\InstanceList($this, $obj->instances);
	}
}
