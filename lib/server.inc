<?php
/**
 * Defines an OpenStack Compute virtual server
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
require_once('metadata.inc');

/**
 * The Server class represents a single server node.
 *
 * A Server is always associated with a (Compute) Service. This implementation
 * supports extension attributes OS-DCF:diskConfig, RAX-SERVER:bandwidth,
 * rax-bandwidth:bandwith
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Server extends \OpenCloud\Compute\NovaInstance {

	const
		JSON_ELEMENT = 'server',
		URL_RESOURCE = 'servers';

	public
		$status,		// server status
		$updated,		// date and time of last update
		$hostId,		// the ID of the host holding the server instance
		$addresses,		// an object holding the server's network addresses
		$links,			// an object with server's permanent and bookmark links
		$image,			// the object object of the server
		$flavor,		// the flavor object of the server
		$networks=array(),	// array of attached networks
		$id,			// the server's ID
		$user_id,		// the user ID that created the server
		$name,			// the server's name
		$created,		// date and time the server was created
		$tenant_id,		// tenant/customer ID that created the server
		$accessIPv4,	// the IPv4 access address
		$accessIPv6,	// the IPv6 access address
		$progress,		// build progress, from 0 (%) to 100 (%)
		$adminPass,		// the root password returned from the Create() method
		$metadata;		// a Metadata object associated with the server

	private
		$imageRef,
		$flavorRef;

	/**
	 * Creates a new Server object and associates it with a Compute service
	 *
	 * @param mixed $info
	 * * If NULL, an empty Server object is created
	 * * If an object, then a Server object is created from the data in the
	 *      object
	 * * If a string, then it's treated as a Server ID and retrieved from the
	 *      service
	 * The normal use case for SDK clients is to treat it as either NULL or an
	 *      ID. The object value parameter is a special case used to construct
	 *      a Server object from a ServerList element to avoid a secondary
	 *      call to the Service.
	 * @throws ServerNotFound if a 404 is returned
	 * @throws UnknownError if another error status is reported
	 */
	public function __construct(\OpenCloud\Compute $service, $info=NULL) {
		// make the service persistent
		parent::__construct($service, $info);

		// the metadata item is an object, not an array
		$this->metadata = $this->Metadata();
	}

	/**
	 * Returns the primary external IP address of the server
	 *
	 * This function is based upon the accessIPv4 and accessIPv6 values.
	 * By default, these are set to the public IP address of the server.
	 * However, these values can be modified by the user; this might happen,
	 * for example, if the server is behind a firewall and needs to be
	 * routed through a NAT device to be reached.
	 *
	 * @api
	 * @param integer $ip_type the type of IP version (4 or 6) to return
	 * @return string IP address
	 */
	public function ip($ip_type=RAXSDK_DEFAULT_IP_VERSION) {
	    switch($ip_type) {
	    case 4:
	        return $this->accessIPv4;
	    case 6:
	        return $this->accessIPv6;
	    default:
	        throw new InvalidIpTypeError(
	            _('Invalid IP address type; must be 4 or 6'));
	    }
	}

	/**
	 * Creates a new server from the data existing in the object
	 *
	 * @api
	 * @param array $params - an associative array of key/value pairs of
	 *      attributes to set on the new server
	 * @param boolean $rebuild - if TRUE, performs a rebuild of an existing
	 *      server
	 * @return HttpResponse - this will include the administrative password
	 *      in the body
	 * @throws \OpenCloud\HttpError
	 * @throws ServerCreateError
	 * @throws UnknownError
	 */
	public function Create($params=array(), $rebuild=FALSE) {
	    // reset values
	    $this->id = NULL;
	    $this->status = NULL;
		foreach($params as $key => $value)
			$this->$key = $value;
		$this->metadata->sdk = RAXSDK_USER_AGENT;
		$this->imageRef = $this->image->links[0]->href;
		$this->flavorRef = $this->flavor->links[0]->href;
		$this->debug(_('Server::Create() [%s]'), $this->name);
		$create = $this->CreateJson( $rebuild ? 'rebuild' : 'server' );
		$response = $this->Service()->Request(
			$this->Service()->Url(),
			'POST',
			array(),
			$create
		);
		if (!is_object($response))
			throw new \OpenCloud\HttpError(sprintf(
			    _('Invalid response for Server::%s() request'),
			    $rebuild ? 'Rebuild' : 'Create'));
		$json = $response->HttpBody();
		if ($response->HttpStatus() >= 300)
			throw new ServerCreateError(
			    sprintf(_('Problem creating server with [%s], '.
			              'status [%d] response [%s]'),
			        $create,
			        $response->HttpStatus(),
			        $response->HttpBody()));
		else if (!$json)
			throw new UnknownError(_('Unexpected error in Server::Create()'));
		else {
			$info = json_decode($json);
			if ($this->CheckJsonError())
				return FALSE;
			foreach($info->server as $item => $value)
				$this->$item = $value;
		}
		return $response;
	}

	/**
	 * Rebuilds an existing server
	 *
	 * @api
	 * @param array $params - an associative array of key/value pairs of
	 *      attributes to set on the new server
     */
    public function Rebuild($params=array()) {
        return $this->Create($params, TRUE);
    }

	/**
	 * Deletes a server
	 *
	 * @api
	 * @return HttpResponse
	 * @throws HttpError, ServerDeleteError
	 */
	public function Delete() {
		$this->debug(_('Server::Delete() [%s]'), $this->Url());
		$response = $this->Service()->Request(
			$this->Url(),
			'DELETE'
		);
		if (!is_object($response))
			throw new \OpenCloud\HttpError(
			    _('Invalid response for Server::delete request'));
		if ($response->HttpStatus() >= 300)
			throw new ServerDeleteError(
				sprintf(_('Problem deleting server [%s] response [%s]'),
					$this->Url(), $response->HttpBody()));
		return $response;
	}

	/**
	 * Updates a server
	 *
	 * Because of the way Nova handles update requests, the only properties you
	 * can modify are the server name and the accessIPvX values.
	 *
	 * @api
	 * @param array $params - optional associative array of key values pairs
	 *      to set on the server before updating it.
	 * @return HttpResponse
	 * @throws HttpError, ServerUpdateError
	 */
	public function Update($params=array()) {
		$this->debug(_('Server::Update() [%s]'), $this->Url());
		foreach($params as $name => $value)
			$this->$name = $value;
		$response = $this->Service()->Request(
			$this->Url(),
			'PUT',
			array(),
			$this->UpdateJson($params)
		);
		if (!is_object($response))
			throw new \OpenCloud\HttpError(
			    _('Invalid response for Server::update request'));
		if ($response->HttpStatus() >= 300) {
			throw new ServerUpdateError(
				sprintf(_('Problem updating server [%s] response [%s]'),
					$this->Url(), $response->HttpBody()));
		}
		return $response;
	}

	/**
	 * Reboots a server
	 *
	 * @api
	 * @param string $type - either 'soft' (the default) or 'hard' to
	 *      indicate the type of reboot
	 * @return boolean TRUE on success; FALSE on failure
	 */
	public function Reboot($type=RAXSDK_SOFT_REBOOT) {
		// create object and json
		$obj = new \stdClass();
		$obj->reboot = new \stdClass();
		$obj->reboot->type = strtoupper($type);
        return $this->Action($obj);
	}

	/**
	 * Creates a new image from a server
	 *
	 * @api
	 * @param string $name The name of the new image
	 * @param array $metadata Optional metadata to be stored on the image
	 * @return boolean TRUE on success; FALSE on failure
	 */
	public function CreateImage($name, $metadata=array()) {
		if (!strlen($name))
			throw new ImageError(
			    _('Image name is required to create an image'));

		// construct a createImage object for jsonization
		$obj = new \stdClass();
		$obj->createImage = new \stdClass();
		$obj->createImage->name = $name;
		$obj->createImage->metadata = new \stdClass();
		foreach($metadata as $name => $value)
			$obj->createImage->metadata->$name = $value;
        return $this->Action($obj);
	}

	/**
	 * Initiates the resize of a server
	 *
	 * @api
	 * @param Flavor $flavorRef a Flavor object indicating the new server size
	 * @return boolean TRUE on success; FALSE on failure
	 */
	public function Resize(Flavor $flavorRef) {
		// construct a resize object for jsonization
		$obj = new \stdClass();
		$obj->resize = new \stdClass();
		$obj->resize->flavorRef = $flavorRef->Url();
        return $this->Action($obj);
	}

	/**
	 * confirms the resize of a server
	 *
	 * @api
	 * @return boolean TRUE on success; FALSE on failure
	 */
	public function ResizeConfirm() {
	    $obj = new \stdClass();
	    $obj->confirmResize = NULL;
        $res = $this->Action($obj);
        $this->Refresh($this->id);
        return $res;
	}

	/**
	 * reverts the resize of a server
	 *
	 * @api
	 * @return boolean TRUE on success; FALSE on failure
	 */
	public function ResizeRevert() {
	    $obj = new \stdClass();
	    $obj->revertResize = NULL;
        return $this->Action($obj);
	}

	/**
	 * Sets the root password on the server
	 *
	 * @api
	 * @param string $newpasswd The new root password for the server
	 * @return boolean TRUE on success; FALSE on failure
	 */
	public function SetPassword($newpasswd) {
		// construct an object to hold the password
		$obj = new \stdClass();
		$obj->changePassword = new \stdClass();
		$obj->changePassword->adminPass = $newpasswd;
        return $this->Action($obj);
	}

	/**
	 * Puts the server into *rescue* mode
	 *
	 * @api
	 * @link http://docs.rackspace.com/servers/api/v2/cs-devguide/content/rescue_mode.html
	 * @return string the root password of the rescue server
	 * @throws ServerActionError if the server has no ID (i.e., has not
	 *      been created yet)
	 */
	public function Rescue() {
	    if (!isset($this->id))
	        throw new ServerActionError(
	            _('Server has no ID; cannot Rescue()'));
	    $obj = new \stdClass();
	    $obj->rescue = "none";
	    $resp = $this->Action($obj);
	    $newobj = json_decode($resp->HttpBody());
	    if ($this->CheckJsonError())
	        return FALSE;
	    elseif (!isset($newobj->adminPass))
	        throw new ServerActionError(sprintf(
	            _('Rescue() method failed unexpectedly, '.
	              'status [%s] response [%s]'),
	            $resp->HttpStatus(), $resp->HttpBody()));
	    else
    	    return $newobj->adminPass;
	}

	/**
	 * Takes the server out of *rescue* mode
	 *
	 * @api
	 * @link http://docs.rackspace.com/servers/api/v2/cs-devguide/content/rescue_mode.html
	 * @return HttpResponse
	 * @throws ServerActionError if the server has no ID (i.e., has not
	 *      been created yet)
     */
    public function Unrescue() {
	    if (!isset($this->id))
	        throw new ServerActionError(
	            _('Server has no ID; cannot Unescue()'));
	    $obj = new \stdClass();
	    $obj->unrescue = NULL;
	    return $this->Action($obj);
    }

	/**
	 * Retrieves the metadata associated with a Server
	 *
	 * If a metadata item name is supplied, then only the single item is
	 * returned. Otherwise, the default is to return all metadata associated
	 * with a server.
	 *
	 * @api
	 * @param string $key - the (optional) name of the metadata item to return
	 * @return OpenCloud\Compute\Metadata object
	 * @throws MetadataError
	 */
	public function Metadata($key=NULL) {
	    /*
		$met = new ServerMetadata($this, $key);
		if (!$key) {
			foreach($this->metadata as $key => $value)
				$met->$key = $value;
		}
		return $met;
		*/
		return new ServerMetadata($this, $key);
	} // function metadata()

	/**
	 * Returns the IP address block for the Server or for a specific network
	 *
	 * @api
	 * @param string $network - if supplied, then only the IP(s) for
	 *      the specified network are returned. Otherwise, all IPs are returned.
	 * @return object
	 * @throws ServerIpsError
	 */
	public function ips($network=NULL) {
		$url = noslash($this->Url('ips/'.$network));
		$response = $this->Service()->Request($url);
		if ($response->HttpStatus() >= 300)
			throw new ServerIpsError(
				sprintf(_('Error in Server::ips(), status [%d], response [%s]'),
					$response->HttpStatus(), $response->HttpBody()));
		$obj = json_decode($response->HttpBody());
		if ($this->CheckJsonError())
			return new \stdClass();
		elseif (isset($obj->addresses))
			return $obj->addresses;
		elseif (isset($obj->network))
			return $obj->network;
		else
			return new \stdClass();
	} // function ips()

	/*********** PROTECTED METHODS ***********/

	/**
	 * Creates the JSON for creating a new server
	 *
	 * @param string $element creates {server ...} by default, but can also
	 *      create {rebuild ...} by changing this parameter
	 * @return json
	 */
	protected function CreateJson($element='server') {
        // create a blank object
        $obj = new \stdClass();
        // set a bunch of properties
        $obj->$element = new \stdClass();
        $obj->$element->name = $this->name;
        $obj->$element->imageRef = $this->imageRef;
        $obj->$element->flavorRef = $this->flavorRef;
        $obj->$element->metadata = $this->metadata;
		if (is_array($this->networks) && count($this->networks)) {
			$obj->$element->networks = array();
			foreach($this->networks as $net) {
			    if (get_class($net) != 'OpenCloud\Compute\Network')
			        throw new Compute\InvalidParameterError(sprintf(
			            _('"networks" parameter must be an array of '.
			              'Compute\Network objects; [%s] found'),
			              get_class($net)));
				$netobj = new \stdClass();
				$netobj->uuid = $net->id;
				$obj->$element->networks[] = $netobj;
			}
		}
        // @TODO(glen) personality is not handled yet
        // handle the $other stuff
        /*
        if (is_array($other))
            foreach($other as $name => $value)
                $obj->$name = $value;
        */
        // return the JSON string
        $json = json_encode($obj);
        return $json;
	}

	/**
	 * creates the JSON for updating a server
	 *
	 * @return json
	 */
	protected function UpdateJson($params) {
		$obj = new \stdClass();
		$obj->server = new \stdClass();
		foreach($params as $name => $value)
			$obj->server->$name = $this->$name;
		$json = json_encode($obj);
		//$this->debug(_('JSON for Server::Update() [%s]'), $json);
		return $json;
	}

	/**
	 * the top-level JSON identifier
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

} // class Server
