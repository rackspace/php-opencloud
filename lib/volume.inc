<?php
/**
 * Defines a block storage volume
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\CBS;

require_once('novainstance.inc');
require_once('metadata.inc');

/**
 * The Volume class represents a single block storage volume
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Volume extends \OpenCloud\Compute\NovaInstance {

	const
		JSON_ELEMENT = 'volume',
		URL_RESOURCE = 'os-volumes';

	public
		$id,
		$display_name,
		$display_description,
		$size,
		$volume_type,
		$metadata = array(),
		$availability_zone,
		$snapshot_id,
		$attachments = array(),
		$created_at;
	
	private
		$CREATE_PROPERTIES = array(
			'display_name',
			'display_description',
			'size',
			'metadata',
			'volume_type',
			'availability_zone'
		);

	/**
	 * Creates a new block storage volume
	 *
	 * @api
	 * @param array $params array of values to set when creating the volume
	 * @return \OpenCloud\HttpResponse
	 * @throws VolumeCreateError if HTTP status is not Success
	 */
	public function Create($params=array()) {
		// set parameters
		foreach($params as $key => $value)
			$this->$key = $value;

		// construct the JSON
		$obj = $this->CreateJson();
		$json = json_encode($obj);
		if ($this->CheckJsonError())
			return FALSE;

		// send the request
		$response = $this->Service()->Request(
			$this->Service()->Url(self::URL_RESOURCE),
			'POST',
			array(),
			$json
		);

		// check the return code
		if ($response->HttpStatus() > 204)
			throw new VolumeCreateError(sprintf(
				_('Error creating volume [%s], status [%d] response [%s]'),
				$this->label, $response->HttpStatus(), $response->HttpBody()));

		// set values from response
		$retobj = json_decode($response->HttpBody());
		if (!$this->CheckJsonError()) {
			$top = $this->JsonName();
			foreach($retobj->$top as $key => $value)
				$this->$key = $value;
		}

		return $response;
	}

	/**
	 * Always throws an error; updates are not permitted
	 *
	 * @throws VolumeUpdateError always
	 */
	public function Update() {
		throw new VolumeUpdateError(_('Block storage volumes cannot be updated'));
	}

	/**
	 * Deletes an block storage volume
	 *
	 * @api
	 * @return \OpenCloud\HttpResponse
	 * @throws VolumeDeleteError if HTTP status is not Success
	 */
	public function Delete() {
		$response = $this->Service()->Request($this->Url(), 'DELETE');
		if ($response->HttpStatus() > 204)
			throw new NetworkDeleteError(sprintf(
				_('Error deleting volume [%s], status [%d] response [%s]'),
				$this->label, $response->HttpStatus(), $response->HttpBody()));
		return $response;
	}

	/********** PROTECTED METHODS **********/

	/**
	 * Returns the top-level document identifier
	 */
	protected function JsonName() {
		return self::JSON_ELEMENT;
	}

	/**
	 * Returns the URL resource
	 */
	protected function ResourceName() {
		return self::URL_RESOURCE;
	}

	/********** PRIVATE METHODS **********/

	/**
	 * Creates the JSON object for the Create() method
	 */
	private function CreateJson() {
		$element = self::JSON_ELEMENT;
		$obj = new \stdClass();
		$obj->$element = new \stdClass();
		foreach ($this->CREATE_PROPERTIES as $name)
			$obj->$element->$name = $this->$name;
		return $obj;
	}

} // class Network
