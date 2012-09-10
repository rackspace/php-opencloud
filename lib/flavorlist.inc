<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud\Compute;

require_once('collection.inc');
require_once('flavor.inc');

/**
 * An FlavorList is a Collection of Flavors
 */
class FlavorList extends \OpenCloud\Collection {
	/**
	 * Retrieves the next flavor in the list, uses factory to produce object
	 */
	public function Next() {
		$item = parent::Next();
		if ($item) {
			$flavor = $this->Service()->Flavor();
			foreach($item as $name => $value)
			    $flavor->$name = $value;
			return $flavor;
		}
		else
			return FALSE;
	} // function Next()
} // class FlavorList
