<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale;

use OpenCloud\OpenStack;
use OpenCloud\Common\Service\AbstractService;

/**
 * The Autoscale class represents the OpenStack Otter service.
 */
class Service extends AbstractService
{
    const DEFAULT_TYPE = 'rax:autoscale';
    const DEFAULT_NAME = 'autoscale';
    
    /**
     * Autoscale resources.
     * 
     * @var    array
     * @access private
     */
    public $resources = array(
        'Group',
        'GroupConfiguration',
        'LaunchConfiguration',
        'ScalingPolicy'
    );
    
    /**
     * Convenience method for getting an autoscale group.
     * 
     * @param  mixed $info
     * @return AbstractResource
     */
    public function group($info = null)
    {
        return $this->resource('Group', $info);
    }
    
    /**
     * Convenience method for getting a list of autoscale groups.
     * 
     * @return OpenCloud\Common\Collection
     */
    public function groupList()
    {
        return $this->resourceList('Group');
    }

}
