<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\OpenStack;

/**
 * The Autoscale class represents the OpenStack Otter service.
 */
class Service extends AbstractService
{
    
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
     * Called when creating a new Autoscale service object
     *
     * _NOTE_ that the order of parameters for this is *different* from the
     * parent Service class. This is because the earlier parameters are the
     * ones that most typically change, whereas the later ones are not
     * modified as often.
     *
     * @param OpenStack $conn - a connection object
     * @param string $serviceRegion - identifies the region of this Compute
     *      service
     * @param string $urltype - identifies the URL type ("publicURL",
     *      "privateURL")
     * @param string $serviceName - identifies the name of the service in the
     *      catalog
     */
    public function __construct(
        OpenStack $conn, 
        $serviceName, 
        $serviceRegion, 
        $urltype, 
        $customEndpoint = null
    ) {

        parent::__construct(
            $conn, 'rax:autoscale', $serviceName, $serviceRegion, $urltype, $customEndpoint
        );
        
        $this->getLogger()->info('Initializing Autoscale...');
    }
    
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
