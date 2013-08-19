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
 * The Image class represents a stored machine image returned by the
 * Compute service.
 *
 * In the future, this may be abstracted to access
 * Glance (the OpenStack image store) directly, but it is currently
 * not available to Rackspace customers, so we're using the /images
 * resource on the servers API endpoint.
 */
class Image extends PersistentObject 
{

    public $status;
    public $updated;
    public $links;
    public $minDisk;
    public $id;
    public $name;
    public $created;
    public $progress;
    public $minRam;
    public $metadata;
    public $server;

    protected static $json_name = 'image';
    protected static $url_resource = 'images';

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

}
