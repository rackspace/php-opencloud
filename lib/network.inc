<?php
/**
 * Defines a virtual network
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Compute;

require_once('novainstance.inc');
require_once('metadata.inc');

/**
 * The Network class represents a single virtual network
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Network extends NovaInstance {

	const
		JSON_ELEMENT = 'network',
		URL_RESOURCE = 'rax-networks';

	public
		$id,
		$label,
		$cidr;
	
	/**
	 * creates a new isolated network
	 *
	 * NOTE: contains hacks to recognize the Rackspace public and private networks.
	 * These are not really networks, but they show up in lists.
	 */
	public function __construct(\OpenCloud\Compute $service, $id=NULL) {
		$this->id = $id;
		switch($id) {
		case RAX_PUBLIC:
			$this->label = 'public';
			return;
		case RAX_PRIVATE:
			$this->label = 'private';
			return;
		default:
			return parent::__construct($service, $id);
		}
	}

	/**
	 * creates a new isolated network
	 *
	 * @api
	 * @param array $params array of values to set when creating the network
	 * @return \OpenCloud\HttpResponse
	 * @throws NetworkCreateError if HTTP status is not Success
	 */
	public function Create($params=array()) {
		// set parameters
		foreach($params as $key => $value)
			$this->$key = $value;
		
		// construct the JSON
		$obj = $this->CreateJson();
		$json = json_encode($obj);
		if ($this->CheckJsonError())
			return FALSE;
		
		// send the request
		$response = $this->Service()->Request(
			$this->Service()->Url(self::URL_RESOURCE),
			'POST',
			array(),
			$json
		);
		
		// check the return code
		if ($response->HttpStatus() > 204)
			throw new NetworkCreateError(sprintf(
				_('Error creating network [%s], status [%d] response [%s]'),
				$this->label, $response->HttpStatus(), $response->HttpBody()));
		
		// set values from response
		$retobj = json_decode($response->HttpBody());
		$top = $this->JsonName();
		if (!$this->CheckJsonError())
			foreach($retobj->$top as $key => $value)
				$this->$key = $value;
		
		return $response;
	}
	
	/**
	 * always throws an error; updates are not permitted
	 *
	 * @throws NetworkUpdateError always
	 */
	public function Update() {
		throw new NetworkUpdateError(_('Isolated networks cannot be updated'));
	}
	
	/**
	 * deletes an isolated network
	 *
	 * @api
	 * @return \OpenCloud\HttpResponse
	 * @throws NetworkDeleteError if HTTP status is not Success
	 */
	public function Delete() {
		$response = $this->Service()->Request($this->Url(), 'DELETE');
		if ($response->HttpStatus() > 204)
			throw new NetworkDeleteError(sprintf(
				_('Error deleting network [%s], status [%d] response [%s]'),
				$this->label, $response->HttpStatus(), $response->HttpBody()));
		return $response;
	}
	
	/********** PROTECTED METHODS **********/

	/**
	 * returns the top-level document identifier
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
	 * creates the JSON object for Create()
	 */
	private function CreateJson() {
		$obj = new \stdClass();
		$obj->network = new \stdClass();
		$obj->network->cidr = $this->cidr;
		$obj->network->label = $this->label;
		return $obj;
	}

} // class Network