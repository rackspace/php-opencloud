<?php
/**
 * The OpenStack Orchestration (Heat) service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Stephen Sugden <openstack@stephensugden.com>
 */

namespace OpenCloud\Network;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\Base\Lang;
use OpenCloud\OpenStack;

/**
 * The OpenStack Quantum service.
 */
class Service extends AbstractService {
    /**
     * @param \OpenCloud\OpenStack $conn connection object
     * @param string $serviceName name of the service (e.g. 'quantum')
     * @param string $serviceRegion identifies the region of this service
     * @param string $urltype the URL type ("publicURL", "privateURL")
     */
    public function __construct(
        OpenStack $conn,
        $serviceName,
        $serviceRegion,
        $urlType
    ) {
        parent::__construct(
            $conn,
            'network',
            $serviceName,
            $serviceRegion,
            $urlType
        );
    }

    /**
     * @api
     * @param string $id - the floating IP with the ID is retrieved
     * @returns FloatingIp
     */
    public function floatingIp($id = null) {
        return new Resource\FloatingIp($this, $id);
    }

    public function namespaces() {
        return array();
    }

    public function port($id = null) {
        return new Resource\Port($this, $id);
    }
}
