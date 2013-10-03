<?php
/**
 * The OpenStack Cinder (Volume) service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Volume;

use OpenCloud\OpenStack;
use OpenCloud\Common\Nova;

class Service extends Nova 
{

	/**
	 * creates the VolumeService object
	 */
	public function __construct(
		OpenStack $connection, 
		$name, 
		$region, 
		$urltype
	) {
		parent::__construct($connection, 'volume', $name, $region, $urltype);
	}

	/**
	 * Returns a Volume object
	 *
	 * @api
	 * @param string $id the Volume ID
	 * @return VolumeService\Volume
	 */
	public function volume($id = null) 
	{
		return new Resource\Volume($this, $id);
	}

	/**
	 * Returns a Collection of Volume objects
	 *
	 * @api
	 * @param boolean $details if TRUE, return all details
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function volumeList($details = true, $filter = array()) 
	{
            $url= $this->url(Resource\Volume::ResourceName()) . ($details ? '/detail' : '');
            return $this->collection('\OpenCloud\Volume\Resource\Volume', $url);
	}

	/**
	 * Returns a VolumeType object
	 *
	 * @api
	 * @param string $id the VolumeType ID
	 * @return VolumeService\Volume
	 */
	public function volumeType($id = null) 
	{
		return new Resource\VolumeType($this, $id);
	}

	/**
	 * Returns a Collection of VolumeType objects
	 *
	 * @api
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function volumeTypeList($filter = array()) 
	{
		return $this->collection('\OpenCloud\Volume\Resource\VolumeType');
	}

	/**
	 * returns a Snapshot object associated with this volume
	 *
	 * @return Snapshot
	 */
	public function snapshot($id = null) 
	{
		return new Resource\Snapshot($this, $id);
	}

	/**
	 * Returns a Collection of Snapshot objects
	 *
	 * @api
	 * @param boolean $detail TRUE to return full details
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function snapshotList($filter = array()) 
	{
		return $this->collection('OpenCloud\Volume\Resource\Snapshot');
	}

}
