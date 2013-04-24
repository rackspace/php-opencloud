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

class Service extends \OpenCloud\AbstractClass\Nova {

	/**
	 * creates the VolumeService object
	 */
	public function __construct(\OpenCloud\OpenStack $conn, $name, $region, $urltype) {
		parent::__construct($conn, 'volume', $name, $region, $urltype);
	}

	/**
	 * Returns a Volume object
	 *
	 * @api
	 * @param string $id the Volume ID
	 * @return VolumeService\Volume
	 */
	public function Volume($id=NULL) {
		return new Volume($this, $id);
	}

	/**
	 * Returns a Collection of Volume objects
	 *
	 * @api
	 * @param boolean $details if TRUE, return all details
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function VolumeList($details=TRUE, $filter=array()) {
		$url = $this->Url(Volume::ResourceName()) .
				($details ? '/detail' : '');
		return $this->Collection('\OpenCloud\Volume\Volume', $url);
	}

	/**
	 * Returns a VolumeType object
	 *
	 * @api
	 * @param string $id the VolumeType ID
	 * @return VolumeService\Volume
	 */
	public function VolumeType($id=NULL) {
		return new VolumeType($this, $id);
	}

	/**
	 * Returns a Collection of VolumeType objects
	 *
	 * @api
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function VolumeTypeList($filter=array()) {
		return $this->Collection('\OpenCloud\Volume\VolumeType');
	}

	/**
	 * returns a Snapshot object associated with this volume
	 *
	 * @return Snapshot
	 */
	public function Snapshot($id=NULL) {
		return new Snapshot($this, $id);
	}

	/**
	 * Returns a Collection of Snapshot objects
	 *
	 * @api
	 * @param boolean $detail TRUE to return full details
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function SnapshotList($filter=array()) {
		return $this->Collection('\OpenCloud\Volume\Snapshot');
	}

}
