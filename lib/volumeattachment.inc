<?php
/**
 * Defines a volume attachment object
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.1
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Compute;

require_once('persistentobject.inc');
require_once('metadata.inc');

/**
 * The LoadBalancer class represents a single load balancer
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class VolumeAttachment extends \OpenCloud\PersistentObject {

	const
		JSON_ELEMENT = 'volumeAttachment',
		URL_RESOURCE = 'os-volume_attachments';

	public
		$id,
		$device,
		$serverId,
		$volumeId;

	private
		$_server,
	    $_create_keys = array(
	        'volumeId',
	        'device'
	    );
	
	/**
	 * creates the object
	 *
	 * This overrides the default constructor so that we can save off the
	 * server to which this attachment is associated.
	 */
	public function __construct(Server $server, $id=NULL) {
		$this->_server = $server;
		parent::__construct($server->Service(), $id);
	}
	
	/**
	 * updates are not permitted
	 *
	 * @throws OpenCloud\UpdateError always
	 */
	public function Update() {
		throw new \OpenCloud\UpdateError(_('Updates are not permitted'));
	}
	
	/**
	 * returns a readable name for the attachment
	 *
	 * Since there is no 'name' attribute, we'll hardcode something
	 *
	 * @api
	 */
	public function Name() {
		return sprintf('Attachment [%s]', $this->id);
	}

	/********** PROTECTED METHODS **********/
	
	/**
	 * returns the URL for Create
	 */
	protected function CreateUrl() {
		return $this->_server->Url(self::URL_RESOURCE);
	}

	/**
	 * returns the JSON object for Create()
	 *
	 * @return stdClass
	 */
	protected function CreateJson() {
	    $obj = new \stdClass();
	    $elem = $this->JsonName();
	    $obj->$elem = new \stdClass();
	    
	    // set the properties
	    foreach($this->_create_keys as $key) {
	    	if ($this->$key) {
	    		$obj->$elem->$key = $this->$key;
	    	}
	    }
	    
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
