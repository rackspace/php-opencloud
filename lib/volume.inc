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

namespace OpenCloud\VolumeService;

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
		JSON_NAME =  'volume',		// JSON element for single item
		URL_RESOURCE = 'volumes';	// URL resource

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
	 * Always throws an error; updates are not permitted
	 *
	 * @throws VolumeUpdateError always
	 */
	public function Update() {
		throw new VolumeUpdateError(
			_('Block storage volumes cannot be updated'));
	}
	
	/**
	 * returns the name of the volume
	 *
	 * @api
	 * @return string
	 */
	public function Name() {
		return $this->display_name;
	}

	/********** PROTECTED METHODS **********/

	/**
	 * Returns the top-level document identifier
	 */
	protected function JsonName() {
		return self::JSON_NAME;
	}

	/**
	 * Returns the URL resource
	 */
	protected function ResourceName() {
		return self::URL_RESOURCE;
	}

	/**
	 * Creates the JSON object for the Create() method
	 */
	protected function CreateJson() {
		$element = $this->JsonName();
		$obj = new \stdClass();
		$obj->$element = new \stdClass();
		foreach ($this->CREATE_PROPERTIES as $name)
			$obj->$element->$name = $this->$name;
		return $obj;
	}

} // class Volume
