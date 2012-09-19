<?php
/**
 * A Cloud Databases instance (similar to a Compute Server)
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\DbService;

require_once('novainstance.inc');
require_once('database.inc');
require_once('user.inc');

/**
 * Instance represents an instance of DbService, similar to a Server in a
 * Compute service
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Instance extends \OpenCloud\Compute\NovaInstance {

	const
		JSON_ELEMENT = 'instance',
		URL_RESOURCE = 'instances';

	public
		$id,
		$name,
		$status,
		$links,
		$hostname,
		$volume,
		$created,
		$updated,
		$flavor;

	private
		$_databases,	// used to Create databases simultaneously
		$_users;		// used to Create users simultaneously

	/**
	 * Creates a new instance object
	 *
	 * This could use the default constructor, but we want to make sure that
	 * the volume attribute is an object.
	 *
	 * @param \OpenCloud\DbService $service the DbService object associated with this
	 * @param mixed $info the ID or array of info for the object
	 */
	public function __construct(\OpenCloud\DbService $service, $info=NULL) {
		$this->volume = new \stdClass();
		return parent::__construct($service, $info);
	}

	/**
	 * creates a new database instance
	 *
	 * @api
	 * @param array $params a hashed array of extra values to set on the object
	 *		prior to creating it
	 * @return \OpenCloud\HttpResponse
	 * @throws InstanceCreateError on unexpected HTTP status response or if the
	 *		response document does not contain `{"instance":...}`
	 */
	public function Create($params=array()) {
		// set parameters
		foreach($params as $property => $value)
			$this->$property = $value;

		// POST the request to the parent collection
	    $url = $this->Service()->Url();
	    $json = json_encode($this->CreateJson($params));
	    if ($this->CheckJsonError())
	    	return FALSE;

	    // send the request
	    $response = $this->Service()->Request($url, 'POST', array(), $json);

	    // check the HTTP status
	    if ($response->HttpStatus() >= 300)
	    	throw new InstanceCreateError(sprintf(
	    		_('Error creating instance, status [%s] response [%s]'),
	    		$response->HttpStatus(),
	    		$response->HttpBody()));

	    // success
	    $this->debug('Instance::Create() response JSON [%s]',
	            $response->HttpBody());
	    $obj = json_decode($response->HttpBody());
	    if (!$this->CheckJsonError()) {
			if (isset($obj->instance))
				$this->Refresh($obj->instance->id);
			else
				throw new InstanceCreateError(sprintf(
					_('"instance" document not found in response [%s]'),
					$response->HttpBody()));
		}
		return $response;
	}

	/**
	 * updates a database instance
	 *
	 * Update() is not supported by database instances
	 *
	 * @throws InstanceUpdateError always
	 */
	public function Update() {
		throw new InstanceUpdateError(
			_('Updates are not currently supported by Cloud Databases'));
	}

	/**
	 * deletes a database instance
	 *
	 * @api
	 * @return \OpenCloud\HttpResponse
	 * @throws InstanceDeleteError if response is not 202
	 */
	public function Delete() {
		$response = $this->Service()->Request($this->Url(), 'DELETE');

		// check return code
		if ($response->HttpStatus() != 202)
			throw new InstanceDeleteError(sprintf(
				_('Error deleting instance, status [%d] response [%s]'),
				$response->HttpStatus(),
				$response->HttpBody()));

		return $response;
	}

	/**
	 * restart the database instance
	 *
	 * @api
	 * @returns \OpenCloud\HttpResponse
	 */
	public function Restart() {
		return $this->Action($this->RestartJson());
	}

	/**
	 * resize the database instance (sets RAM)
	 *
	 * @api
	 * @param \OpenCloud\Compute\Flavor $flavor a flavor object
	 * @returns \OpenCloud\HttpResponse
	 */
	public function Resize(\OpenCloud\Compute\Flavor $flavor) {
		return $this->Action($this->ResizeJson($flavor));
	}

	/**
	 * resizes the volume associated with the database instance (disk space)
	 *
	 * @api
	 * @param integer $newvolumesize the size of the new volume, in gigabytes
	 * @return \OpenCloud\HttpResponse
	 */
	public function ResizeVolume($newvolumesize) {
		return $this->Action($this->ResizeVolumeJson($newvolumesize));
	}

	/**
	 * enables the root user for the instance
	 *
	 * @api
	 * @return User the root user, including name and password
	 * @throws InstanceError if HTTP response is not Success
	 */
	public function EnableRootUser() {
		$response = $this->Service()->Request($this->Url('root'), 'POST');

		// check response
		if ($response->HttpStatus() > 202)
			throw New InstanceError(sprintf(
				_('Error enabling root user for instance [%s], '.
				  'status [%d] response [%s]'),
				$this->name, $response->HttpStatus(), $response->HttpBody()));

		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;
		if (isset($obj->user))
			return new User($this, $obj->user);
		else
			return FALSE;
	}

	/**
	 * returns TRUE if the root user is enabled
	 *
	 * @api
	 * @return boolean TRUE if the root user is enabled; FALSE otherwise
	 * @throws InstanceError if HTTP status is not Success
	 */
	public function IsRootEnabled() {
		$response = $this->Service()->Request($this->Url('root'), 'GET');

		// check response
		if ($response->HttpStatus() > 202)
			throw New InstanceError(sprintf(
				_('Error enabling root user for instance [%s], '.
				  'status [%d] response [%s]'),
				$this->name, $response->HttpStatus(), $response->HttpBody()));

		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return FALSE;
		if (isset($obj->rootEnabled) && $obj->rootEnabled)
			return TRUE;
		else
			return FALSE;
	}

	/********** FACTORY METHODS **********/

	/**
	 * returns a new database
	 *
	 * @param string $name the database name
	 * @return Database
	 */
	public function Database($name='') {
	    return new Database($this, $name);
	}

	/**
	 * returns a new user
	 *
	 * @param string $name the user name
	 * @param array $databases a simple array of database names
	 * @return User
	 */
	public function User($name='', $databases=array()) {
	    return new User($this, $name, $databases);
	}

	/**
	 * returns a Collection of all databases in the instance
	 *
	 * @return Collection
	 * @throws DatabaseListError if HTTP status is not Success
	 */
	public function DatabaseList() {
		$response = $this->Service()->Request($this->Url('databases'));

		// check response status
		if ($response->HttpStatus() > 200)
			throw new DatabaseListError(sprintf(
				_('Error listing databases for instance [%s], status [%d] response [%s]'),
				$this->name, $response->HttpStatus(), $response->HttpBody()));

		// parse the response
		$obj = json_decode($response->HttpBody());
		if (!$this->CheckJsonError()) {
			if (!isset($obj->databases))
				return new \OpenCloud\Collection($this, 'Database', array());
			return new \OpenCloud\Collection($this, 'Database', $obj->databases);
		}
		return FALSE;
	}

	/**
	 * returns a Collection of all users in the instance
	 *
	 * @return Collection
	 * @throws UserListError if HTTP status is not Success
	 */
	public function UserList() {
		$response = $this->Service()->Request($this->Url('users'));

		// check response status
		if ($response->HttpStatus() > 200)
			throw new UserListError(sprintf(
				_('Error listing users for instance [%s], status [%d] response [%s]'),
				$this->name, $response->HttpStatus(), $response->HttpBody()));

		// parse the response
		$obj = json_decode($response->HttpBody());
		if (!$this->CheckJsonError()) {
			if (!isset($obj->users))
				return new \OpenCloud\Collection($this, 'User', array());
			return new \OpenCloud\Collection($this, 'User', $obj->users);
		}
		return FALSE;
	}

	/********** PROTECTED METHODS **********/

	/**
	 * the top-level document name
	 */
	protected function JsonName() {
		return self::JSON_ELEMENT;
	}
	
	/**
	 * the URL resource
	 */
	protected function ResourceName() {
		return self::URL_RESOURCE;
	}

	/********** PRIVATE METHODS **********/

	/**
	 * generates the JSON string for Create()
	 *
	 * @return \stdClass
	 */
	private function CreateJson($params) {
	    $obj = new \stdClass();
	    $obj->instance = new \stdClass();

	    // copy data from params
	    foreach($params as $property => $value)
	    	$obj->instance->$property = $value;

	    // flavor
	    if (!isset($this->flavor))
	        throw new InstanceFlavorError(
	            _('a flavor must be specified'));
	    if (!is_object($this->flavor))
	        throw new InstanceFlavorError(
	            _('the [flavor] attribute must be a Flavor object'));
	    $obj->instance->flavorRef = $this->flavor->links[0]->href;

	    // name
	    if (!isset($this->name))
	        throw new InstanceError(
	            _('Instance name is required'));
	    $obj->instance->name = $this->name;

	    // volume
	    $obj->instance->volume = $this->volume;

		return $obj;
	}

	/**
	 * generates the JSON object for Restart
	 */
	private function RestartJson() {
		$obj = new \stdClass();
		$obj->restart = new \stdClass();
		return $obj;
	}

	/**
	 * generates the JSON object for Resize
	 */
	private function ResizeJson($flavorRef) {
		$obj = new \stdClass();
		$obj->resize = new \stdClass();
		$obj->resize->flavorRef = $flavorRef;
		return $obj;
	}

	/**
	 * generates the JSON object for ResizeVolume
	 */
	private function ResizeVolumeJson($size) {
		$obj = new \stdClass();
		$obj->resize = new \stdClass();
		$obj->resize->volume = new \stdClass();
		$obj->resize->volume->size = $size;
		return $obj;
	}

}
