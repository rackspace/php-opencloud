<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud\Compute;

require_once('collection.inc');
require_once('image.inc');

/**
 * An ImageList is a Collection of Images
 */
class ImageList extends \OpenCloud\Collection {
	/**
	 * Retrieves the next image in the list, uses factory to produce object
	 */
	public function Next() {
		$item = parent::Next();
		if ($item) {
			$image = $this->Service()->Image();
			foreach($item as $name => $value)
			    $image->$name = $value;
			return $image;
		}
		else
			return FALSE;
	} // function Next()
} // class ImageList
