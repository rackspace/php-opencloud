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

namespace OpenCloud\Database;

use OpenCloud\Common\Nova;
use OpenCloud\OpenStack;

/**
 * The Rackspace Database As A Service (aka "Red Dwarf")
 */
class Service extends Nova
{

    /**
     * Creates a new DbService service connection
     *
     * This is not normally called directly, but via the factory method on the
     * OpenStack or Rackspace connection object.
     *
     * @param OpenStack $conn the connection on which to create the service
     * @param string $name the name of the service (e.g., "cloudDatabases")
     * @param string $region the region of the service (e.g., "DFW" or "LON")
     * @param string $urltype the type of URL (normally "publicURL")
     */
    public function __construct(OpenStack $conn, $name, $region, $urltype)
    {
        parent::__construct($conn, 'rax:database', $name, $region, $urltype);
    }

    /**
     * Returns the URL of this database service, or optionally that of
     * an instance
     *
     * @param string $resource the resource required
     * @param array $args extra arguments to pass to the URL as query strings
     */
    public function url($resource = 'instances', array $args = array())
    {
        return parent::url($resource, $args);
    }

    /**
     * Returns a list of flavors
     *
     * just call the parent FlavorList() method, but pass FALSE
     * because the /flavors/detail resource is not supported
     *
     * @api
     * @return \OpenCloud\Compute\FlavorList
     */
    public function flavorList($details = false, array $filter = array())
    {
        return parent::flavorList(false);
    }

    /**
     * Creates a Instance object
     *
     * @api
     * @param string $id the ID of the instance to retrieve
     * @return DbService\Instance
     */
    public function instance($id = null)
    {
        return new Instance($this, $id);
    }

    /**
     * Creates a Collection of Instance objects
     *
     * @api
     * @param array $params array of parameters to pass to the request as
     *      query strings
     * @return Collection
     */
    public function instanceList($params = array())
    {
        return $this->collection('OpenCloud\Database\Instance', null, null, $params);
    }
}
