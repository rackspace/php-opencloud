<?php
/**
 * Defines a block storage snapshot
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
 * The Snapshot class represents a single block storage snapshot
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Snapshot extends \OpenCloud\Compute\NovaInstance {

	const
		JSON_NAME = 'snapshot',
		URL_RESOURCE = 'os-snapshots';
		
	public
		$id,
		$display_name,
		$display_description,
		$volume_id,
		$status,
		$size,
		$created_at;
	
	private
		$force,
		$_create_keys=array('display_name','display_description',
							'volume_id','force');
	
	/**
	 * updates are not permitted
	 *
	 */
	public function Update() {
		throw new \OpenCloud\UpdateError(
			_('VolumeType cannot be updated'));
	}
		
	/********** PROTECTED METHODS **********/
	
	/**
	 * returns the object for the Create() method's JSON
	 *
	 * @return stdClass
	 */
	protected function CreateJson() {
		$obj = new \stdClass();
		
		$elem = $this->JsonName();
		$obj->$elem = new \stdClass();
		foreach($this->_create_keys as $key)
			$obj->$elem->$key = $this->$key;
		
		return $obj;
	}

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

}
