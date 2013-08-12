<?php
/**
 * The Rackspace Autoscale (Otter) service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Stephen Sugden <openstack@stephensugden.com>
 */

namespace OpenCloud\Autoscale;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\Base\Lang;
use OpenCloud\OpenStack;

/**
 * The Autoscale class represents the OpenStack Heat service.
 *
 * It is constructed from a OpenStack object and requires a service name,
 * region, and URL type to select the proper endpoint from the service
 * catalog. However, constants can be used to define default values for
 * these to make it easier to use:
 *
 */
class Service extends AbstractService {

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
        $urltype
    ) {
        $this->debug(_('initializing Autoscale...'));
        parent::__construct(
            $conn,
            'rax:autoscale',
            $serviceName,
            $serviceRegion,
            $urltype
        );
    }

}
