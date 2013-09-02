<?php
/**
 * The OpenStack Orchestration (Heat) service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Stephen Sugden <openstack@stephensugden.com>
 */

namespace OpenCloud\Orchestration;

use OpenCloud\Common\Service as AbstractService;
use OpenCloud\Base\Lang;
use OpenCloud\OpenStack;

/**
 * The Orchestration class represents the OpenStack Heat service.
 *
 * Heat is a service to orchestrate multiple composite cloud applications using 
 * the AWS CloudFormation template format, through both an OpenStack-native ReST 
 * API and a CloudFormation-compatible Query API.
 * 
 * @codeCoverageIgnore
 */
class Service extends AbstractService 
{

    /**
     * {@inheritDoc}
     */
    public function __construct(
        OpenStack $conn,
        $serviceName,
        $serviceRegion,
        $urltype
    ) {
        
        $this->getLogger()->info('Initializing Orchestration...');
        
        parent::__construct(
            $conn,
            'orchestration',
            $serviceName,
            $serviceRegion,
            $urltype
        );
    }

    /**
     * Returns a Stack object associated with this Orchestration service
     *
     * @api
     * @param string $id - the stack with the ID is retrieved
     * @returns Stack object
     */
    public function stack($id = null) 
    {
        return new Stack($this, $id);
    }
    
    /**
     * Return namespaces.
     * 
     * @return array
     */
    public function namespaces() 
    {
        return array();
    }
}
