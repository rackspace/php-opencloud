<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud\DbService;

require_once('collection.inc');
require_once('user.inc');

/**
 * UserList is a Collection of Users
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class UserList extends \OpenCloud\Collection {
	/**
	 * Retrieves the next instance in the list, uses factory to produce object
	 */
	public function Next() {
		$item = parent::Next();
		if ($item) {
			return $this->Service()->User($item);
		}
		else
			return FALSE;
	} // function Next()
}
