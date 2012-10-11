<?php
/**
 * Defines a block storage volume type
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
 * The VolumeType class represents a single block storage volume type
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class VolumeType extends \OpenCloud\PersistentObject {

	const
		JSON_ELEMENT = 'volume_type',
		URL_RESOURCE = 'types';
		
	public
		$id,
		$name,
		$extra_specs;
	
	/**
	 * Creates are not permitted
	 *
	 */
	public function Create() {
		throw new \OpenCloud\CreateError(
			_('VolumeType cannot be created'));
	}
		
	/**
	 * updates are not permitted
	 *
	 */
	public function Update() {
		throw new \OpenCloud\UpdateError(
			_('VolumeType cannot be updated'));
	}
		
	/**
	 * deletes are not permitted
	 *
	 */
	public function Delete() {
		throw new \OpenCloud\DeleteError(
			_('VolumeType cannot be deleted'));
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

}
