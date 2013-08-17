<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale\Resource;

/**
 * This configuration specifies what to do when we want to create a new server. 
 * What image to boot, on what flavor, and which load balancer to connect it to.
 * 
 * The Launch Configuration Contains:
 * 
 * - Launch Configuration Type (Only type currently supported is "launch_server")
 * - Arguments:
 *  - Server
 *   - name
 *   - flavor
 *   - imageRef (This is the ID of the Cloud Server image you will boot)
 *  - Load Balancer
 *   - loadBalancerId
 *   - port
 * 
 * @link https://github.com/rackerlabs/otter/blob/master/doc/getting_started.rst
 * @link http://docs.autoscale.apiary.io/
 */
class LaunchConfiguration extends AbstractResource
{
    
    public $type;
    public $args;
    
    protected static $json_name = 'launchConfiguration';
    protected static $url_resource = 'launch';
    
    /**
     * {@inheritDoc}
     */
    public function create($params = array())
    {
        $this->noCreate();
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->noDelete();
    }
    
}