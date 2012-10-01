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

require_once('novainstance.inc');

/**
 * The Image class represents a stored machine image returned by the
 * Compute service.
 *
 * In the future, this may be abstracted to access
 * Glance (the OpenStack image store) directly, but it is currently
 * not available to Rackspace customers, so we're using the /images
 * resource on the servers API endpoint.
 */
class Image extends NovaInstance {

	const
		JSON_NAME = 'image',
		URL_RESOURCE = 'images';

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
	
	/**
	 * error on Create
	 */
	public function Create() {
		throw new \OpenCloud\CreateError(
			_('Images cannot be created directly'));
	}
	
	/**
	 * error on Update()
	 */
	public function Update() {
		throw new \OpenCloud\CreateError(
			_('Images cannot be updated directly'));
	}

    /**
     * Validates extension attributes
     */
    public function __set($name, $value) {
        $this->SetProperty($name, $value, $this->Service()->namespaces());
    }
    
    /********** PROTECTED METHODS **********/
    
    protected function ResourceName() {
    	return self::URL_RESOURCE;
    }
    
    protected function JsonName() {
    	return self::JSON_NAME;
    }

} // class Image
