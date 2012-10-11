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

require_once('persistentobject.inc');
require_once('metadata.inc');

/**
 * The Volume class represents a single block storage volume
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Volume extends \OpenCloud\PersistentObject {

	const
		JSON_ELEMENT =  'volume',		// JSON element for single item
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
		$_create_keys = array(
		    'snapshot_id',
			'display_name',
			'display_description',
			'size',
			'volume_type',
			'availability_zone'
		);

	/**
	 * Always throws an error; updates are not permitted
	 *
	 * @throws VolumeUpdateError always
	 */
	public function Update() {
		throw new \OpenCloud\UpdateError(
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
		return self::JSON_ELEMENT;
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
		foreach ($this->_create_keys as $name) {
			if ($this->$name) {
			    switch($name) {
			    case 'volume_type':
			        $obj->$element->$name = $this->volume_type->Name();
			        break;
			    default:
				    $obj->$element->$name = $this->$name;
				}
			}
		}
		if (is_array($this->metadata) && count($this->metadata)) {
			$obj->$element->metadata = new \stdClass();
			foreach($this->metadata as $key => $value)
				$obj->$element->metadata->$key = $value;
		}
		return $obj;
	}

} // class Volume
