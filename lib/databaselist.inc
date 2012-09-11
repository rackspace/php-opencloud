<?php
/**
 * A Collection of Database objects
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\DbService;

require_once('collection.inc');
require_once('database.inc');

/**
 * DatabaseList is a Collection of Databases
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class DatabaseList extends \OpenCloud\Collection {
	/**
	 * Retrieves the next instance in the list, uses factory to produce object
	 */
	public function Next() {
		$item = parent::Next();
		if ($item) {
			return $this->Service()->Database($item);
		}
		else
			return FALSE;
	} // function Next()
}
