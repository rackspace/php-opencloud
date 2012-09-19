<?php
/**
 * A Collection of Network objects
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Compute;

require_once('collection.inc');
require_once('network.inc');

/**
 * NetworkList is a Collection of Network objects
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class NetworkList extends \OpenCloud\Collection {
	/**
	 * Retrieves the next server in the next, uses factory to produce object
	 */
	public function Next() {
		$item = parent::Next();
		if ($item)
			return $this->Service()->Network($item);
		else
			return FALSE;
	} // function Next()
}
