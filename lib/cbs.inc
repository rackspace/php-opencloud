<?php
/**
 * The Rackspace Cloud Block Storage (CBS) service
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

require_once('volume.inc');
require_once('volumetype.inc');

class CBS extends Service {

	/**
	 * creates the CBS object
	 */
	public function __construct(Rackspace $conn, $name, $region, $urltype) {
		parent::__construct($conn, 'volume', $name, $region, $urltype);
	}

	/**
	 * Returns a Volume object
	 *
	 * @api
	 * @param string $id the Volume ID
	 * @return Compute\Volume
	 */
	public function Volume($id=NULL) {
		return new Compute\Volume($this, $id);
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
		$url = $this->Url(Compute\Volume::URL_RESOURCE);
		if ($details)
			$url .= '/detail';
		$response = $this->Request($url);

		// check return code
		if ($response->HttpStatus() > 202)
			throw new Compute\VolumeError(sprintf(
				_('Unable to retrieve network info [%s], '.
				  'status [%d] response [%s]'),
				$url, $response->HttpStatus(), $response->HttpBody()));

		// handle empty response
		if (strlen($response->HttpBody()) == 0)
			return new Collection($this, 'Volume', array());

		// parse the return
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;

		// and say goodbye
		if (isset($obj->volumes))
			return new Collection($this, 'Volume', $obj->volumes);
		else
			return new Collection($this, 'Volume', array());
	}

	/**
	 * Returns a VolumeType object
	 *
	 * @api
	 * @param string $id the VolumeType ID
	 * @return Compute\Volume
	 */
	public function VolumeType($id=NULL) {
		return new Compute\VolumeType($this, $id);
	}

	/**
	 * Returns a Collection of VolumeType objects
	 *
	 * @api
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function VolumeTypeList($filter=array()) {
		$url = $this->Url(Compute\VolumeType::URL_RESOURCE);
		$response = $this->Request($url);

		// check return code
		if ($response->HttpStatus() > 202)
			throw new Compute\VolumeTypeError(sprintf(
				_('Unable to retrieve volume type info [%s], '.
				  'status [%d] response [%s]'),
				$url, $response->HttpStatus(), $response->HttpBody()));

		// handle empty response
		if (strlen($response->HttpBody()) == 0)
			return new Collection($this, 'VolumeType', array());

		// parse the return
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;

		// and say goodbye
		if (isset($obj->networks))
			return new Collection($this, 'VolumeType', $obj->networks);
		else
			return new Collection($this, 'VolumeType', array());
	}

}