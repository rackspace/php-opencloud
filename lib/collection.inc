<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

namespace OpenCloud;

require_once('base.inc');

/**
 * Provides an abstraction for working with ordered sets of objects
 *
 * Collection objects are used whenever there are multiples; for example,
 * multiple objects in a container, or multiple servers in a service.
 *
 * @since 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
abstract class Collection extends Base {
private
	$service,
	$itemlist=array(),
	$pointer=0;

	/**
	 * a Collection is an array of objects
	 *
	 * @param Service $service - the service associated with the collection
	 * @param array $arr - the input array
	 */
	public function __construct($service, $arr) {
		$this->service = $service;
		if (!is_array($arr))
			throw new \OpenCloud\CollectionError(
				_('Cannot create a Collection without an array'));
		$this->itemlist=$arr;
	}

	/**
	 * retrieves the service associated with the Collection
	 *
	 * @return Service
	 */
	public function service() {
		return $this->service;
	}

	/**
	 * Resets the collection pointer back to the first item in the set
	 *
	 * @return Base the first item in the set
	 */
	public function First() {
		$this->pointer = 0;
		return $this->Next();
	}

	/**
	 * Returns the next item in the set
	 *
	 * @return Base the next item or FALSE if at the end of the set
	 */
	public function Next() {
		if ($this->pointer >= count($this->itemlist))
			return FALSE;
		return $this->itemlist[$this->pointer++];
	}

	/**
	 * Returns the number of items in the collection
	 *
	 * @return integer The number of items in the set
	 */
	public function Size() {
	    return count($this->itemlist);
	}

} // class Collection
