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

/**
 * The Flavor class represents a flavor defined by the Compute service
 *
 * At its simplest, a Flavor represents a combination of RAM, disk space,
 * and compute CPUs, though there are other extended attributes.
 */
class Flavor extends PersistentObject 
{

    public $status;
    public $updated;
    public $vcpus;
    public $disk;
    public $name;
    public $links;
    public $rxtx_factor;
    public $ram;
    public $id;
    public $swap;

    protected static $json_name = 'flavor';
    protected static $url_resource = 'flavors';
    
    /**
     * {@inheritDoc}
     */
    public function create($params = array()) 
    { 
        return $this->noCreate(); 
    }
    
    /**
     * {@inheritDoc}
     */
    public function update($params = array()) 
    { 
        return $this->noUpdate(); 
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete() 
    { 
        return $this->noDelete(); 
    }

}
