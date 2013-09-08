<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Compute;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Volume\Volume;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;

/**
 * The Server class represents a single server node.
 *
 * A Server is always associated with a (Compute) Service. This implementation
 * supports extension attributes OS-DCF:diskConfig, RAX-SERVER:bandwidth,
 * rax-bandwidth:bandwith
 */
class Server extends PersistentObject
{
    // Ideally these should have data types defined in docblocks

    public $status;             // Server status
    public $updated;            // date and time of last update
    public $hostId;             // the ID of the host holding the server instance
    public $addresses;          // an object holding the server's network addresses
    public $links;              // an object with server's permanent and bookmark links
    public $image;              // the object object of the server
    public $flavor;             // the flavor object of the server
    public $networks = array(); // array of attached networks
    public $id;                 // the server's ID
    public $user_id;            // the user ID that created the server
    public $name;               // the server's name
    public $created;            // date and time the server was created
    public $tenant_id;          // tenant/customer ID that created the server
    public $accessIPv4;         // the IPv4 access address
    public $accessIPv6;         // the IPv6 access address
    public $progress;           // build progress, from 0 (%) to 100 (%)
    public $adminPass;          // the root password returned from the Create() method
    public $metadata;           // a Metadata object associated with the server

    protected static $json_name = 'server';
    protected static $url_resource = 'servers';

    private $personality = array(); // uploaded file attachments
    private $imageRef;              // image reference (for create)
    private $flavorRef;             // flavor reference (for create)

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
    public function __construct(Service $service, $info = null)
    {
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
    public function ip($ip_type = RAXSDK_DEFAULT_IP_VERSION)
    {
        switch($ip_type) {
            case 4:
                return $this->accessIPv4;
            case 6:
                return $this->accessIPv6;
            default:
                throw new Exceptions\InvalidIpTypeError(Lang::translate('Invalid IP address type; must be 4 or 6'));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function create($params = array())
    {
        $this->id     = null;
        $this->status = null;
        
        return parent::create($params);
    }
    
    /**
     * {@inheritDoc}
     */
    public function createUrl()
    {
        return $this->getService()->url();
    }

    /**
     * Rebuilds an existing server
     *
     * @api
     * @param array $params - an associative array of key/value pairs of
     *      attributes to set on the new server
     */
    public function rebuild($params = array())
    {
    	if (!isset($params['adminPass'])) {
    		throw new Exceptions\RebuildError(
    			Lang::Translate('adminPass required when rebuilding server')
            );
        }
        
        if (!isset($params['image'])) {
    		throw new Exceptions\RebuildError(
    			Lang::Translate('image required when rebuilding server')
            );
        }
        
    	$obj = new \stdClass();
    	$obj->rebuild = new \stdClass();
    	$obj->rebuild->imageRef = $params['image']->Id();
    	$obj->rebuild->adminPass = $params['adminPass'];
        return $this->Action($obj);
    }

    /**
     * Reboots a server
     *
     * You can pass the parameter RAXSDK_SOFT_REBOOT (default) or
     * RAXSDK_HARD_REBOOT to specify the type of reboot. A "soft" reboot
     * requests that the operating system reboot itself; a "hard" reboot
     * is the equivalent of pulling the power plug and then turning it back
     * on, with a possibility of data loss.
     *
     * @api
     * @param string $type - either 'soft' (the default) or 'hard' to
     *      indicate the type of reboot
     * @return boolean TRUE on success; FALSE on failure
     */
    public function reboot($type = RAXSDK_SOFT_REBOOT)
    {
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
    public function createImage($name, $metadata = array())
    {
        if (empty($name)) {
            throw new Exceptions\ImageError(
            	Lang::translate('Image name is required to create an image')
            );
        }

        // construct a createImage object for jsonization
        $obj = new \stdClass;
        $obj->createImage = new \stdClass;
        $obj->createImage->name = $name;
        $obj->createImage->metadata = new \stdClass;

        foreach ($metadata as $name => $value) {
            $obj->createImage->metadata->$name = $value;
        }

        $response = $this->action($obj);
        
        if (!$response || !($location = $response->header('Location'))) {
            return false;
        }

        return new Image($this->getService(), basename($location));
    }

    /**
     * Schedule daily image backups
     *
     * @api
     * @param mixed $retention - false (default) indicates you want to
     *      retrieve the image schedule. $retention <= 0 indicates you
     *      want to delete the current schedule. $retention > 0 indicates
     *      you want to schedule image backups and you would like to
     *      retain $retention backups.
     * @return mixed an object or FALSE on error
     * @throws ServerImageScheduleError if an error is encountered
     */
    public function imageSchedule($retention = false)
    {
        $url = Lang::noslash($this->url('rax-si-image-schedule'));

        $response = null;

        if ($retention === false) { 
            // Get current retention
            $response = $this->getService()->request($url);
        } elseif ($retention <= 0) { 
            // Delete image schedule
            $response = $this->getService()->request($url, 'DELETE');
        } else { 
            // Set image schedule
            $object = new \stdClass();
            $object->image_schedule = new \stdClass();
            $object->image_schedule->retention = $retention;
            
            $response = $this->getService()->request($url, 'POST', array(), json_encode($object));
        }
        
        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() >= 300) {
            throw new Exceptions\ServerImageScheduleError(sprintf(
                Lang::translate('Error in Server::ImageSchedule(), status [%d], response [%s]'),
                $response->HttpStatus(),
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

        $object = json_decode($response->HttpBody());
        
        if ($object && property_exists($object, 'image_schedule'))
            return $object->image_schedule;
        else {
            return new \stdClass;
        }
    }

    /**
     * Initiates the resize of a server
     *
     * @api
     * @param Flavor $flavorRef a Flavor object indicating the new server size
     * @return boolean TRUE on success; FALSE on failure
     */
    public function resize(Flavor $flavorRef)
    {
        // construct a resize object for jsonization
        $obj = new \stdClass();
        $obj->resize = new \stdClass();
        $obj->resize->flavorRef = $flavorRef->id;
        return $this->Action($obj);
    }

    /**
     * confirms the resize of a server
     *
     * @api
     * @return boolean TRUE on success; FALSE on failure
     */
    public function resizeConfirm()
    {
        $obj = new \stdClass();
        $obj->confirmResize = null;
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
    public function resizeRevert()
    {
        $obj = new \stdClass();
        $obj->revertResize = null;
        return $this->Action($obj);
    }

    /**
     * Sets the root password on the server
     *
     * @api
     * @param string $newpasswd The new root password for the server
     * @return boolean TRUE on success; FALSE on failure
     */
    public function setPassword($newpasswd)
    {
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
    public function rescue()
    {
        $this->checkExtension('os-rescue');

        if (empty($this->id)) {
            throw new Exceptions\ServerActionError(
                Lang::translate('Server has no ID; cannot Rescue()')
            );
        }

        $obj = new \stdClass;
        $obj->rescue = "none";

        $resp = $this->action($obj);
        $newobj = json_decode($resp->httpBody());

        $this->checkJsonError();
        
        // @codeCoverageIgnoreStart
        if (!isset($newobj->adminPass)) {
            throw new Exceptions\ServerActionError(sprintf(
                Lang::translate('Rescue() method failed unexpectedly, status [%s] response [%s]'),
                $resp->httpStatus(),
                $resp->httpBody()
            ));
        // @codeCoverageIgnoreEnd
            
        } else {
            return $newobj->adminPass;
        }
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
    public function unrescue()
    {
        $this->CheckExtension('os-rescue');

        if (!isset($this->id)) {
            throw new Exceptions\ServerActionError(Lang::translate('Server has no ID; cannot Unescue()'));
        }

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
    public function metadata($key = null)
    {
        return new ServerMetadata($this, $key);
    }

    /**
     * Returns the IP address block for the Server or for a specific network
     *
     * @api
     * @param string $network - if supplied, then only the IP(s) for
     *      the specified network are returned. Otherwise, all IPs are returned.
     * @return object
     * @throws ServerIpsError
     */
    public function ips($network = null)
    {
        $url = Lang::noslash($this->Url('ips/'.$network));

        $response = $this->Service()->Request($url);
        
        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() >= 300) {
            throw new Exceptions\ServerIpsError(sprintf(
                Lang::translate('Error in Server::ips(), status [%d], response [%s]'),
                $response->HttpStatus(),
                $response->HttpBody()
            ));
        }
        
        $object = json_decode($response->httpBody());
        
        $this->checkJsonError();
 
        if (isset($object->addresses)) {
            return $object->addresses;
        } elseif (isset($object->network)) {
            return $object->network;
        } else {
            return new \stdClass;
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Attaches a volume to a server
     *
     * Requires the os-volumes extension. This is a synonym for
     * `VolumeAttachment::Create()`
     *
     * @api
     * @param OpenCloud\VolumeService\Volume $vol the volume to attach. If
     *      `"auto"` is specified (the default), then the first available
     *      device is used to mount the volume (for example, if the primary
     *      disk is on `/dev/xvhda`, then the new volume would be attached
     *      to `/dev/xvhdb`).
     * @param string $device the device to which to attach it
     */
    public function attachVolume(Volume $volume, $device = 'auto')
    {
        $this->CheckExtension('os-volumes');

        return $this->VolumeAttachment()->Create(array(
            'volumeId'  => $volume->id,
            'device'    => ($device=='auto' ? NULL : $device)
        ));
    }

    /**
     * removes a volume attachment from a server
     *
     * Requires the os-volumes extension. This is a synonym for
     * `VolumeAttachment::Delete()`
     *
     * @api
     * @param OpenCloud\VolumeService\Volume $vol the volume to remove
     * @throws VolumeError
     */
    public function detachVolume(Volume $volume)
    {
        $this->CheckExtension('os-volumes');
        return $this->VolumeAttachment($volume->id)->Delete();
    }

    /**
     * returns a VolumeAttachment object
     *
     */
    public function volumeAttachment($id = null)
    {
        $resource = new VolumeAttachment($this->getService());
        $resource->setParent($this);
        $resource->populate($id);
        return $resource;
    }

    /**
     * returns a Collection of VolumeAttachment objects
     *
     * @api
     * @return Collection
     */
    public function volumeAttachmentList()
    {
        return $this->getService()->collection(
            '\OpenCloud\Compute\VolumeAttachment',
            NULL,
            $this
        );
    }

    /**
     * adds a "personality" file to be uploaded during Create() or Rebuild()
     *
     * The `$path` argument specifies where the file will be stored on the
     * target server; the `$data` is the actual data values to be stored.
     * To upload a local file, use `file_get_contents('name')` for the `$data`
     * value.
     *
     * @api
     * @param string $path the file path (up to 255 characters)
     * @param string $data the file contents (max size set by provider)
     * @return void
     * @throws PersonalityError if server already exists (has an ID)
     */
    public function addFile($path, $data)
    {
        // set the value
        $this->personality[$path] = base64_encode($data);
    }

	/**
	 * Returns a console connection
     * Note: Where is this documented?
     * 
     * @codeCoverageIgnore
	 */
    public function console($type = 'novnc') 
    {
        $info = new \stdClass;
        $info->type = $type;
        $msg = new \stdClass;
        $action = (strpos('spice', $type) !== false) ? 'os-getSPICEConsole' : 'os-getVNCConsole';
        $msg->$action = $info;
        return json_decode($this->action($msg)->httpBody())->console;
    }


    /**
     * Creates the JSON for creating a new server
     *
     * @param string $element creates {server ...} by default, but can also
     *      create {rebuild ...} by changing this parameter
     * @return json
     */
    protected function createJson()
    {
        // Convert some values
        $this->metadata->sdk = RAXSDK_USER_AGENT;
        
        if (!empty($this->image) && $this->image instanceof Image) {
            $this->imageRef = $this->image->id;
        }
        if (!empty($this->flavor) && $this->flavor instanceof Flavor) {
            $this->flavorRef = $this->flavor->id;
        }
        
        // Base object
        $server = (object) array(
            'name'        => $this->name,
            'imageRef'    => $this->imageRef,
            'flavorRef'   => $this->flavorRef,
            'metadata'    => $this->metadata,
            'networks'    => array(),
            'personality' => array()
        );
        
        // Networks
		if (is_array($this->networks) && count($this->networks)) {
			foreach ($this->networks as $network) {
				if (!$network instanceof Network) {
					throw new Exceptions\InvalidParameterError(sprintf(
						'When creating a server, the "networks" key must be an ' .
						'array of OpenCloud\Compute\Network objects with valid ' .
                        'IDs; variable passed in was a [%s]',
						gettype($network)
					));
				}
                if (empty($network->id)) {
                    $this->getLogger()->warning('When creating a server, the ' 
                        . 'network objects passed in must have an ID'
                    );
                    continue;
                }
                // Stock networks array
				$server->networks[] = (object) array('uuid' => $network->id);
			}
		}

        // Personality files
        if (!empty($this->personality)) {
            foreach ($this->personality as $path => $data) {
                // Stock personality array
                $server->personality[] = (object) array(
                    'path'     => $path,
                    'contents' => $data
                );
            }
        }
        
        return (object) array('server' => $server);
    }

    /**
     * Creates the JSON for updating a server
     *
     * @return json
     */
    protected function updateJson($params = array())
    {
        $object = new \stdClass();
        $object->server = new \stdClass();
        foreach($params as $name => $value) {
            $object->server->$name = $this->$name;
        }
        return $object;
    }

}
