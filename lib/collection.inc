<?php
/**
 * An abstraction for working with ordered sets of objects
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

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
class Collection extends Base {

	private
		$service,
		$itemclass,
		$itemlist=array(),
		$pointer=0,
		$sortkey;

	/**
	 * A Collection is an array of objects
	 *
	 * @param Service $service - the service associated with the collection
	 * @param string $itemclass - the Class of each item in the collection
	 *		(assumed to be the name of the factory method)
	 * @param array $arr - the input array
	 */
	public function __construct($service, $itemclass, $arr) {
		$this->service = $service;
		$this->itemclass = $itemclass;
		if (!is_array($arr))
			throw new \OpenCloud\CollectionError(
				_('Cannot create a Collection without an array'));
		$this->itemlist=$arr;
	}

	/**
	 * Retrieves the service associated with the Collection
	 *
	 * @return Service
	 */
	public function Service() {
		return $this->service;
	}

	/**
	 * Resets the pointer to the beginning, but does NOT return the first item
	 *
	 * @api
	 * @return void
	 */
	public function Reset() {
		$this->pointer = 0;
	}

	/**
	 * Resets the collection pointer back to the first item in the set
	 * and returns it
	 *
	 * This is useful if you're only interested in the first item in the set.
	 *
	 * @api
	 * @return Base the first item in the set
	 */
	public function First() {
		$this->Reset();
		return $this->Next();
	}

	/**
	 * Returns the next item in the set
	 *
	 * @api
	 * @return Base the next item or FALSE if at the end of the set
	 */
	public function Next() {
		if ($this->pointer >= count($this->itemlist))
			return FALSE;
		$class = $this->itemclass;
		return $this->Service()->$class($this->itemlist[$this->pointer++]);
	}

	/**
	 * Returns the number of items in the collection
	 *
	 * @api
	 * @return integer The number of items in the set
	 */
	public function Size() {
	    return count($this->itemlist);
	}

	/**
	 * sorts the collection on a specified key
	 *
	 * Note: only top-level keys can be used as the sort key
	 *
	 * @api
	 * @param string $keyname the name of the field to use as the sort key
	 * @return void
	 */
	public function Sort($keyname='id') {
		$this->sortkey = $keyname;
		usort($this->itemlist, array($this, 'sortCompare'));
	}

	/**
	 * selects only specified items from the Collection
	 *
	 * This provides a simple form of filtering on Collections. For each item
	 * in the collection, it calls the callback function, passing it the item.
	 * If the callback returns `TRUE`, then the item is retained; if it returns
	 * `FALSE`, then the item is deleted from the collection.
	 *
	 * Note that this should not supersede server-side filtering; the
	 * `Collection::Select()` method requires that *all* of the data for the
	 * Collection be retrieved from the server before the filtering is
	 * performed; this can be very inefficient, especially for large data
	 * sets. This method is mostly useful on smaller-sized sets.
	 *
	 * Example:
	 * <code>
	 * $services = $connection->ServiceList();
	 * $services->Select(function($item){ return $item->region=='ORD';});
	 * // now the $services Collection only has items from the ORD region
	 * </code>
	 *
	 * `Select()` is *destructive*; that is, it actually removes entries from
	 * the collection. For example, if you use `Select()` to find items with
	 * the ID > 10, then use it again to find items that are <= 10, it will
	 * return an empty list.
	 *
	 * @api
	 * @param callable $testfunc a callback function that is passed each item
	 *		in turn. Note that `Select()` performs an explicit test for
	 *		`FALSE`, so functions like `strpos()` need to be cast into a
	 *		boolean value (and not just return the integer).
	 * @returns void
	 * @throws DomainError if callback doesn't return a boolean value
	 */
	public function Select($testfunc) {
		foreach($this->itemlist as $index => $item) {
			$test = call_user_func($testfunc, $item);
			if (!is_bool($test))
				throw new DomainError(
					_('Callback function for Collection::Select() '.
					  'did not return boolean'));
			if ($test === FALSE)
				unset($this->itemlist[$index]);
		}
	}

	/********** PRIVATE METHODS **********/

	/**
	 * Compares two values of sort keys
	 */
	private function sortCompare($a, $b) {
		$key = $this->sortkey;

		// handle strings with strcmp()
		if (is_string($a->$key))
			return strcmp($a->$key, $b->$key);

		// handle others with logical comparisons
		if ($a->$key == $b->$key)
			return 0;
		if ($a->$key < $b->$key)
			return -1;
		else
			return 1;
	}

} // class Collection
