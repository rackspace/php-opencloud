<?php
/**
 * The Rackspace Autoscale (Otter) service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 * @package phpOpenCloud
 * @version 1.5.9
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\OpenStack;

/**
 * The Autoscale class represents the OpenStack Otter service.
 *
 * It is constructed from a OpenStack object and requires a service name,
 * region, and URL type to select the proper endpoint from the service
 * catalog. However, constants can be used to define default values for
 * these to make it easier to use:
 *
 */
class Service extends AbstractService
{
    
    /**
     * Autoscale resources.
     * 
     * @var     array
     * @access  private
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
    
    public function group($info = null)
    {
        return $this->resource('Group', $info);
    }
    
    public function groupList()
    {
        return $this->resourceList('Group');
    }

}
