<?php
/**
 * A Collection of DataObjects in the Object Storage service
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

/**
 * The ObjectList class defines a Collection of data objects from the
 * Object Storage Service
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class ObjectList extends \OpenCloud\Collection {

    private
        $container;

    /**
     * Builds a new list of objects from a container
     *
     * Note that, since the ObjectStore hierarchy is three levels deep
     * (ObjectStore -> Container -> Object), we need to store off the
     * container object HERE before calling the parent Collection, which
     * stores the service.
     *
     * @param Container $container the container holding the objects
     * @param array $objects a list of DataObjects
     */
    public function __construct(Container $container, $objects) {
        $this->container = $container;
        parent::__construct($this->container->Service(), $objects);
    }

    /**
     * retrieves the next DataObject from the list
     *
     * @return DataObject
     * @return FALSE if at the end of the list
     */
	public function Next() {
		$item = parent::Next();
		if ($item)
			return $this->container->Object($item);
		else
			return FALSE;
	} // function Next()
} // class ObjectList
