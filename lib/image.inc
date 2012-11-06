<?php
/**
 * An object that defines a virtual machine image
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Compute;

require_once('persistentobject.inc');

/**
 * The Image class represents a stored machine image returned by the
 * Compute service.
 *
 * In the future, this may be abstracted to access
 * Glance (the OpenStack image store) directly, but it is currently
 * not available to Rackspace customers, so we're using the /images
 * resource on the servers API endpoint.
 */
class Image extends \OpenCloud\PersistentObject {

    public
		$status,
		$updated,
		$links,
		$minDisk,
		$id,
		$name,
		$created,
		$progress,
		$minRam,
		$metadata,
		$server;
	
	protected static
		$json_name = 'image',
		$url_resource = 'images';
	
	/**
	 * error on Create()
	 *
	 * The Create() method is not supported on the compute/image class.
	 *
	 * @throws OpenCloud\CreateError always
	 */
	public function Create() {
		throw new \OpenCloud\CreateError(
			_('Images cannot be created directly'));
	}
	
	/**
	 * error on Update()
	 *
	 * The Update() method is not supported on the compute/image class.
	 *
	 * @throws OpenCloud\UpdateError always
	 */
	public function Update() {
		throw new \OpenCloud\UpdateError(
			_('Images cannot be updated directly'));
	}

} // class Image
