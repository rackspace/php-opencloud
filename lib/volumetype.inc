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

namespace OpenCloud\CBS;

require_once('novainstance.inc');
require_once('metadata.inc');

/**
 * The VolumeType class represents a single block storage volume type
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class VolumeType extends \OpenCloud\Compute\NovaInstance {

	const
		JSON_ELEMENT = 'volume_type',
		URL_RESOURCE = 'volume_types';
		
	public
		$id,
		$name,
		$extra_specs;

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
