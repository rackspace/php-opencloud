<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud\Compute;

require_once('collection.inc');
require_once('server.inc');

/**
 * ServerList is a Collection of servers
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class ServerList extends \OpenCloud\Collection {
	/**
	 * Retrieves the next server in the next, uses factory to produce object
	 */
	public function Next() {
		$item = parent::Next();
		if ($item) {
			//return $this->Service()->Server($item->id);
			$server = $this->Service()->Server($item);
			return $server;
		}
		else
			return FALSE;
	} // function Next()
}
