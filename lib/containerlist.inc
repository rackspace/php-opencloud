<?php
/**
 * A Collection of Container objects
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

require_once('collection.inc');
require_once('container.inc');

/**
 * A ContainerList is a Collection of Containers
 */
class ContainerList extends \OpenCloud\Collection {
    /**
     * retrieves the next Container from the list
     *
     * @return OpenCloud\ObjectStore\Container
     * @return FALSE if at the end of the list
     */
	public function Next() {
		$item = parent::Next();
		if ($item)
			return $this->Service()->Container($item);
		else
			return FALSE;
	} // function Next()
}
