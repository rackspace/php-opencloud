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
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;

/**
 * The Network class represents a single virtual network
 */
class Network extends PersistentObject 
{

    public $id;
    public $label;
    public $cidr;
    
    protected static $json_name = 'network';
    protected static $url_resource = 'os-networksv2';

    /**
     * Creates a new isolated Network object
     *
     * NOTE: contains hacks to recognize the Rackspace public and private
     * networks. These are not really networks, but they show up in lists.
     *
     * @param \OpenCloud\Compute $service The compute service associated with
     *      the network
     * @param string $id The ID of the network (this handles the pseudo-networks
     *      RAX_PUBLIC and RAX_PRIVATE
     * @return void
     */
    public function __construct(Service $service, $id = null) 
    {
        $this->id = $id;

        switch ($id) {
            case RAX_PUBLIC:
                $this->label = 'public';
                $this->cidr = 'NA';
                break;
            case RAX_PRIVATE:
                $this->label = 'private';
                $this->cidr = 'NA';
                break;
            default:
                return parent::__construct($service, $id);
        }
        
        return;
    }

    /**
     * Always throws an error; updates are not permitted
     *
     * @throws NetworkUpdateError always
     */
    public function Update($params = array()) 
    {
        throw new Exceptions\NetworkUpdateError(Lang::translate('Isolated networks cannot be updated'));
    }

    /**
     * Deletes an isolated network
     *
     * @api
     * @return \OpenCloud\HttpResponse
     * @throws NetworkDeleteError if HTTP status is not Success
     */
    public function Delete() 
    {
        switch ($this->id) {
            case RAX_PUBLIC:
            case RAX_PRIVATE:
                throw new Exceptions\DeleteError('Network may not be deleted');
            default:
                return parent::Delete();
        }
    }
    
    /**
     * returns the visible name (label) of the network
     *
     * @api
     * @return string
     */
    public function Name() 
    {
        return $this->label;
    }

    /**
     * Creates the JSON object for the Create() method
     */
    protected function CreateJson() 
    {
        $obj = new \stdClass();
        $obj->network = new \stdClass();
        $obj->network->cidr = $this->cidr;
        $obj->network->label = $this->label;
        return $obj;
    }

}
