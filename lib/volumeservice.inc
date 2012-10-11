<?php
/**
 * The OpenStack Cinder (Volume) service
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

require_once('nova.inc');
require_once('volume.inc');
require_once('volumetype.inc');
require_once('snapshot.inc');

class VolumeService extends Nova {

	/**
	 * creates the VolumeService object
	 */
	public function __construct(OpenStack $conn, $name, $region, $urltype) {
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
		return new VolumeService\Volume($this, $id);
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
		$url = $this->Url(VolumeService\Volume::URL_RESOURCE);
		if ($details)
			$url .= '/detail';
		$this->debug('VolumeList [%s]', $url);
		$response = $this->Request($url);

		// check return code
		if ($response->HttpStatus() > 202)
			throw new VolumeService\VolumeError(sprintf(
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
		$element = VolumeService\Volume::JSON_ELEMENT.'s';
		if (isset($obj->$element))
			return new Collection($this, 'Volume', $obj->$element);
		else
			return new Collection($this, 'Volume', array());
	}

	/**
	 * Returns a VolumeType object
	 *
	 * @api
	 * @param string $id the VolumeType ID
	 * @return VolumeService\Volume
	 */
	public function VolumeType($id=NULL) {
		return new VolumeService\VolumeType($this, $id);
	}

	/**
	 * Returns a Collection of VolumeType objects
	 *
	 * @api
	 * @param array $filters array of filter key/value pairs
	 * @return Collection
	 */
	public function VolumeTypeList($filter=array()) {
		$url = $this->Url(VolumeService\VolumeType::URL_RESOURCE);
		$response = $this->Request($url);
		$this->debug('VolumeTypeList [%s]', $url);

		// check return code
		if ($response->HttpStatus() > 202)
			throw new VolumeService\VolumeTypeError(sprintf(
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
		$element = VolumeService\VolumeType::JSON_ELEMENT.'s';
		if (isset($obj->$element))
			return new Collection($this, 'VolumeType', $obj->$element);
		else
			return new Collection($this, 'VolumeType', array());
	}

	/**
	 * returns a Snapshot object associated with this volume
	 *
	 * @return Snapshot
	 */
	public function Snapshot($id=NULL) {
		return new VolumeService\Snapshot($this, $id);
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
		$url = $this->Url(VolumeService\Snapshot::URL_RESOURCE);
		$response = $this->Request($url);
		$this->debug('SnapshotList [%s]', $url);

		// check return code
		if ($response->HttpStatus() > 202)
			throw new VolumeService\SnapshotError(sprintf(
				_('Unable to retrieve Snapshot info [%s], '.
				  'status [%d] response [%s]'),
				$url, $response->HttpStatus(), $response->HttpBody()));

		// handle empty response
		if (strlen($response->HttpBody()) == 0)
			return new Collection($this, 'Snapshot', array());

		// parse the return
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;

		// and say goodbye
		$element = VolumeService\Snapshot::JSON_ELEMENT.'s';
		if (isset($obj->$element))
			return new Collection($this, 'Snapshot', $obj->$element);
		else
			return new Collection($this, 'Snapshot', array());
	}

    /**
     * Gets a request from an HTTP source and ensures that the
     * content type is always "application/json"
     *
     * This is a simple subclass of the parent::Request() method that ensures
     * that all Compute requests use application/json as the Content-Type:
     *
     * @param string $url - the URL of the request
     * @param string $method - the HTTP method ("GET" by default)
     * @param array $headers - an associative array of headers to pass to
     *      the request
     * @param string $body - optional body for POST or PUT requests
     * @return \Rackspace\HttpResult object
     */
	public function Request($url, $method='GET', $headers=array(), $body=NULL) {
		$headers['Content-Type'] = 'application/json';
		return parent::Request($url, $method, $headers, $body);
	}

}
