<?php
/**
 * Containers for OpenStack Object Storage (Swift) and Rackspace Cloud Files
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

use OpenCloud\Base\Lang;
use OpenCloud\Base\Exceptions;

/**
 * A simple container for the CDN Service
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class CDNContainer extends \OpenCloud\AbstractClass\ObjectStore {

    public
        $name,
        $count=0,
        $bytes=0;
    private
        $service,
        $container_url,
        $_cdn;

    /**
     * Creates the container object
     *
     * Creates a new container object or, if the $cdata object is a string,
     * retrieves the named container from the object store. If $cdata is an
     * array or an object, then its values are used to set this object.
     *
     * @param OpenCloud\ObjectStore $service - the ObjectStore service
     * @param mixed $cdata - if supplied, the name of the object
     */
	public function __construct(\OpenCloud\AbstractClass\Service $service, $cdata=NULL) {
		$this->debug(Lang::translate('Initializing Container...'));
		parent::__construct();
		$this->service = $service;

		// set values from an object (via containerlist)
		if (is_object($cdata)) {
		    foreach($cdata as $name => $value)
		        if ($name == 'metadata')
		            $this->metadata->SetArray($value);
		        else
    		        $this->$name = $value;
		}
		// or, if it's a string, retrieve the object with that name
		else if ($cdata) {
			$this->debug(Lang::translate('Getting container [%s]'), $cdata);
			$this->name = $cdata;
			$this->Refresh();
		}
	} // __construct()

    /**
     * Returns the URL of the container
     *
     * @return string
     * @throws NoNameError
     */
	public function Url() {
		if (!$this->name)
			throw new \OpenCloud\Base\Exceptions\NoNameError(Lang::translate('Container does not have an identifier'));
		return Lang::noslash($this->Service()->Url(
			rawurlencode($this->name)));
	}

	/**
	 * Creates a new container with the specified attributes
	 *
	 * @param array $params array of parameters
	 * @return boolean TRUE on success; FALSE on failure
	 * @throws ContainerCreateError
	 */
	public function Create($params=array()) {
		foreach($params as $name => $value) {
			switch($name) {
			case 'name':
				if ($this->is_valid_name($value))
					$this->name = $value;
				break;
			default:
				$this->$name = $value;
			}
		}
		$this->container_url = $this->Url();
		$headers = $this->MetadataHeaders();
		$response = $this->Service()->Request(
			$this->Url(),
			'PUT',
			$headers
		);

		// check return code
		if ($response->HttpStatus() > 202) {
			throw new \OpenCloud\Base\Exceptions\ContainerCreateError(
				sprintf(Lang::translate('Problem creating container [%s] status [%d] '.
				          'response [%s]'),
					$this->Url(),
					$response->HttpStatus(),
					$response->HttpBody()));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Updates the metadata for a container
	 *
	 * @return boolean TRUE on success; FALSE on failure
	 * @throws ContainerCreateError
	 */
	public function Update() {
		$headers = $this->MetadataHeaders();
		$response = $this->Service()->Request(
			$this->Url(),
			'POST',
			$headers
		);

		// check return code
		if ($response->HttpStatus() > 204) {
			throw new \OpenCloud\Base\Exceptions\ContainerCreateError(
				sprintf(Lang::translate('Problem updating container [%s] status [%d] '.
				          'response [%s]'),
					$this->Url(),
					$response->HttpStatus(),
					$response->HttpBody()));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Deletes the specified container
	 *
	 * @return boolean TRUE on success; FALSE on failure
	 * @throws ContainerDeleteError
	 */
	public function Delete() {
		$response = $this->Service()->Request(
			$this->Url(),
			'DELETE'
		);

		// validate the response code
		if ($response->HttpStatus() == 404)
			throw new \OpenCloud\Base\Exceptions\ContainerNotFoundError(sprintf(
				Lang::translate('Container [%s] not found'), $this->name));

		if ($response->HttpStatus() == 409)
			throw new \OpenCloud\Base\Exceptions\ContainerNotEmptyError(sprintf(
				Lang::translate('Container [%s] must be empty before deleting'),
				  $this->name));

		if ($response->HttpStatus() >= 300) {
			throw new \OpenCloud\Base\Exceptions\ContainerDeleteError(
				sprintf(Lang::translate('Problem deleting container [%s] status [%d] '.
				            'response [%s]'),
					$this->Url(),
					$response->HttpStatus(),
					$response->HttpBody()));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Creates a Collection of objects in the container
	 *
	 * @param array $params associative array of parameter values.
     * * account/tenant	- The unique identifier of the account/tenant.
     * * container- The unique identifier of the container.
     * * limit (Optional) - The number limit of results.
     * * marker (Optional) - Value of the marker, that the object names
     *      greater in value than are returned.
     * * end_marker (Optional) - Value of the marker, that the object names
     *      less in value than are returned.
     * * prefix (Optional) - Value of the prefix, which the returned object
     *      names begin with.
     * * format (Optional) - Value of the serialized response format, either
     *      json or xml.
     * * delimiter (Optional) - Value of the delimiter, that all the object
     *      names nested in the container are returned.
	 * @link http://api.openstack.org for a list of possible parameter
	 *      names and values
	 * @return OpenCloud\Collection
	 * @throws ObjFetchError
	 */
	public function ObjectList($params=array()) {
		// construct a query string out of the parameters
		$params['format'] = 'json';
		$qstring = $this->MakeQueryString($params);

		// append the query string to the URL
		$url = $this->Url();
		if (strlen($qstring) > 0)
			$url .= '?' . $qstring;

		// fetch it
		return $this->Service()->Collection(
		    '\OpenCloud\ObjectStore\DataObject', $url, $this);
	} // object_list()

	/**
	 * Returns a new DataObject associated with this container
	 *
	 * @param string $name if supplied, the name of the object to return
	 * @return DataObject
	 */
	public function DataObject($name=NULL) {
		return new DataObject($this, $name);
	}

	/**
	 * Returns the Service associated with the Container
	 */
	public function Service() {
		return $this->service;
	}

	/********** PRIVATE METHODS **********/

	/**
	 * Loads the object from the service
	 *
	 * @return void
	 */
	protected function Refresh()
	{
		$response = $this->Service()->Request($this->Url(), 'HEAD', array('Accept'=>'*/*'));

        // validate the response code
        if ($this->name != 'TEST') {
			if ($response->HttpStatus() == 404)
				throw new \OpenCloud\Base\Exceptions\ContainerNotFoundError(sprintf(
					Lang::translate('Container [%s] (%s) not found'),
						$this->name, $this->Url()));

			if ($response->HttpStatus() >= 300)
				throw new \OpenCloud\Base\Exceptions\HttpError(
					sprintf(
						Lang::translate('Error retrieving Container, status [%d]'.
						' response [%s]'),
						$response->HttpStatus(),
						$response->HttpBody()));
	    }
        // parse the returned object
        $this->GetMetadata($response);
	}

	/**
	 * Validates that the container name is acceptable
	 *
	 * @param string $name the container name to validate
	 * @return boolean TRUE if ok; throws an exception if not
	 * @throws ContainerNameError
	 */
	private function is_valid_name($name) {
		if (($name == NULL) || ($name == ''))
			throw new \OpenCloud\Base\Exceptions\ContainerNameError(
			    Lang::translate('Container name cannot be blank'));
		if ($name == '0')
			throw new \OpenCloud\Base\Exceptions\ContainerNameError(
			    Lang::translate('"0" is not a valid container name'));
		if (strpos($name, '/') !== FALSE)
			throw new \OpenCloud\Base\Exceptions\ContainerNameError(
			    Lang::translate('Container name cannot contain "/"'));
		if (strlen($name) > \OpenCloud\ObjectStore\Service::MAX_CONTAINER_NAME_LEN)
			throw new \OpenCloud\Base\Exceptions\ContainerNameError(
			    Lang::translate('Container name is too long'));
		return TRUE;
	}

} // class CDNContainer
