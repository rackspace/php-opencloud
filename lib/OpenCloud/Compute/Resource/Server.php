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

namespace OpenCloud\Compute\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Volume\Resource\Volume;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Compute\Service;

/**
 * A virtual machine (VM) instance in the Cloud Servers environment.
 *
 * @note This implementation supports extension attributes OS-DCF:diskConfig, 
 * RAX-SERVER:bandwidth, rax-bandwidth:bandwith.
 */
class Server extends PersistentObject
{
    /**
     * The server status. Supported types are: 
     * 
     * * ACTIVE: The server is active and ready to use.
     * * BUILD: The server is being built.
     * * DELETED: The server was deleted. The list servers API operation does 
     *      not show servers with a status of DELETED. To list deleted servers, 
     *      use the changes-since parameter.
     * * ERROR: The requested operation failed and the server is in an error state.
     * * HARD_REBOOT: The server is going through a hard reboot. This power 
     *      cycles your server, which performs an immediate shutdown and restart.
     * * MIGRATING: The server is being moved from one physical node to 
     *      another physical node. Server migration is a Rackspace extension.
     * * PASSWORD: The password for the server is being changed.
     * * REBOOT: The server is going through a soft reboot. During a soft reboot, 
     *      the operating system is signaled to restart, which allows for a 
     *      graceful shutdown and restart of all processes.
     * * REBUILD: The server is being rebuilt from an image.
     * * RESCUE: The server is in rescue mode. Rescue mode is a Rackspace extension.
     * * RESIZE: The server is being resized and is inactive until it completes.
     * * REVERT_RESIZE: A resized or migrated server is being reverted to its 
     *      previous size. The destination server is being cleaned up and the 
     *      original source server is restarting. Server migration is a Rackspace extension.
     * * SUSPENDED: The server is inactive, either by request or necessity.
     * * UNKNOWN: The server is in an unknown state.
     * * VERIFY_RESIZE: The server is waiting for the resize operation to be 
     *      confirmed so that the original server can be removed.
     * 
     * @var string 
     */
    public $status;
    
    /**
     * @var string The time stamp for the last update.
     */
    public $updated;
    
    /**
     * The compute provisioning algorithm has an anti-affinity property that 
     * attempts to spread customer VMs across hosts. Under certain situations, 
     * VMs from the same customer might be placed on the same host. $hostId 
     * represents the host your server runs on and can be used to determine this 
     * scenario if it is relevant to your application.
     * 
     * @var string 
     */
    public $hostId;
    
    /**
     * @var type Public and private IP addresses for this server.
     */
    public $addresses;
    
    /**
     * @var array Server links.
     */
    public $links;
    
    /**
     * The Image for this server.
     * 
     * @link http://docs.rackspace.com/servers/api/v2/cs-devguide/content/List_Images-d1e4435.html
     * @var type 
     */
    public $image;
    
    /**
     * The Flavor for this server.
     * 
     * @link http://docs.rackspace.com/servers/api/v2/cs-devguide/content/List_Flavors-d1e4188.html
     * @var type 
     */
    public $flavor;
    
    /**
     * @var type 
     */
    public $networks = array();
    
    /**
     * @var string The server ID.
     */
    public $id;
    
    /**
     * @var string The user ID.
     */
    public $user_id;
    
    /**
     * @var string The server name.
     */
    public $name;
    
    /**
     * @var string The time stamp for the creation date.
     */
    public $created;
    
    /**
     * @var string The tenant ID.
     */
    public $tenant_id;
    
    /** 
     * @var string The public IP version 4 access address.
     */
    public $accessIPv4;
    
    /**
     * @var string The public IP version 6 access address.
     */
    public $accessIPv6;
    
    /**
     * The build completion progress, as a percentage. Value is from 0 to 100.

     * @var int 
     */
    public $progress;
    
    /**
     * @var string The root password (only populated on server creation).
     */
    public $adminPass;
    
    /**
     * @var mixed Metadata key and value pairs.
     */
    public $metadata;

    protected static $json_name = 'server';
    protected static $url_resource = 'servers';
    
    /**
     * @var array Uploaded file attachments
     */
    private $personality = array();
    
    /**
     * @var type Image reference (for create)
     */
    private $imageRef;
    
    /**
     * @var type Flavor reference (for create)
     */
    private $flavorRef;

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
        $this->metadata = $this->metadata();
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
        
        $object = (object) array(
            'rebuild' => (object) array(
                'imageRef'  => $params['image']->id(),
                'adminPass' => $params['adminPass']
            )
        );
        
        return $this->action($object);
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
        $object = (object) array(
            'reboot' => (object) array('type' => strtoupper($type))
        );
        return $this->action($object);
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
        $object = (object) array('createImage' => (object) array(
            'name'     => $name, 
            'metadata' => (object) $metadata
        ));

        $response = $this->action($object);
        
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
        $object = (object) array(
            'resize' => (object) array('flavorRef' => $flavorRef->id)
        );
        return $this->action($object);
    }

    /**
     * confirms the resize of a server
     *
     * @api
     * @return boolean TRUE on success; FALSE on failure
     */
    public function resizeConfirm()
    {
        $object = (object) array('confirmResize' => null);
        $response = $this->action($object);
        $this->refresh($this->id);
        return $response;
    }

    /**
     * reverts the resize of a server
     *
     * @api
     * @return boolean TRUE on success; FALSE on failure
     */
    public function resizeRevert()
    {
        $object = (object) array('revertResize' => null);
        return $this->action($object);
    }

    /**
     * Sets the root password on the server
     *
     * @api
     * @param string $newpasswd The new root password for the server
     * @return boolean TRUE on success; FALSE on failure
     */
    public function setPassword($newPassword)
    {
        $object = (object) array(
            'changePassword' => (object) array('adminPass' => $newPassword)
        );
        return $this->action($object);
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

        $data = (object) array('rescue' => 'none');

        $response = $this->action($data);
        $object = json_decode($response->httpBody());
        $this->checkJsonError();
        
        // @codeCoverageIgnoreStart
        if (!isset($object->adminPass)) {
            throw new Exceptions\ServerActionError(sprintf(
                Lang::translate('Rescue() method failed unexpectedly, status [%s] response [%s]'),
                $response->httpStatus(),
                $response->httpBody()
            ));
        // @codeCoverageIgnoreEnd
            
        } else {
            return $object->adminPass;
        }
    }

    /**
     * Takes the server out of RESCUE mode
     *
     * @api
     * @link http://docs.rackspace.com/servers/api/v2/cs-devguide/content/rescue_mode.html
     * @return HttpResponse
     * @throws ServerActionError if the server has no ID (i.e., has not
     *      been created yet)
     */
    public function unrescue()
    {
        $this->checkExtension('os-rescue');

        if (!isset($this->id)) {
            throw new Exceptions\ServerActionError(Lang::translate('Server has no ID; cannot Unescue()'));
        }

        $object = (object) array('unrescue' => null);
        return $this->action($object);
    }

    /**
     * Retrieves the metadata associated with a Server.
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
     * Returns the IP address block for the Server or for a specific network.
     *
     * @api
     * @param string $network - if supplied, then only the IP(s) for the
     *       specified network are returned. Otherwise, all IPs are returned.
     * @return object
     * @throws ServerIpsError
     */
    public function ips($network = null)
    {
        $url = Lang::noslash($this->Url('ips/'.$network));

        $response = $this->getService()->request($url);
        
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
            $returnObject = $object->addresses;
        } elseif (isset($object->network)) {
            $returnObject = $object->network;
        } else {
            $returnObject = (object) array();
        }
        
        return $object;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Attaches a volume to a server
     *
     * Requires the os-volumes extension. This is a synonym for
     * `VolumeAttachment::create()`
     *
     * @api
     * @param OpenCloud\Volume\Resource\Volume $volume The volume to attach. If
     *      "auto" is specified (the default), then the first available
     *      device is used to mount the volume (for example, if the primary
     *      disk is on `/dev/xvhda`, then the new volume would be attached
     *      to `/dev/xvhdb`).
     * @param string $device the device to which to attach it
     */
    public function attachVolume(Volume $volume, $device = 'auto')
    {
        $this->checkExtension('os-volumes');
        return $this->volumeAttachment()->create(array(
            'volumeId'  => $volume->id,
            'device'    => ($device == 'auto' ? null : $device)
        ));
    }

    /**
     * Removes a volume attachment from a server
     *
     * Requires the os-volumes extension. This is a synonym for
     * `VolumeAttachment::delete()`

     * @param OpenCloud\Volume\Resource\Volume $volume The volume to remove
     */
    public function detachVolume(Volume $volume)
    {
        $this->checkExtension('os-volumes');
        return $this->volumeAttachment($volume->id)->Delete();
    }

    /**
     * Returns a VolumeAttachment object
     *
     */
    public function volumeAttachment($id = null)
    {
        $resource = new VolumeAttachment($this->getService());
        $resource->setParent($this)->populate($id);
        return $resource;
    }

    /**
     * Returns a Collection of VolumeAttachment objects

     * @return Collection
     */
    public function volumeAttachmentList()
    {
        return $this->getService()->collection(
            'OpenCloud\Compute\Resource\VolumeAttachment', null, $this
        );
    }

    /**
     * Adds a "personality" file to be uploaded during create() or rebuild()
     *
     * @api
     * @param string $path The path where the file will be stored on the
     *      target server (up to 255 characters)
     * @param string $data the file contents (max size set by provider)
     * @return void
     */
    public function addFile($path, $data)
    {
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
        $action = (strpos('spice', $type) !== false) ? 'os-getSPICEConsole' : 'os-getVNCConsole';
        $object = (object) array($action => (object) array('type' => $type));
        return json_decode($this->action($object)->httpBody())->console;
    }


    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    protected function updateJson($params = array())
    {
        return (object) array('server' => (object) $params);
    }

}
