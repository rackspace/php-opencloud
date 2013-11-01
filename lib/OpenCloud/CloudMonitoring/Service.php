<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\CloudMonitoring;

use OpenCloud\OpenStack;
use OpenCloud\Common\Service\AbstractService;

class Service extends AbstractService
{
    
    const DEFAULT_NAME = 'cloudMonitoring';
    
    /**
     * CloudMonitoring resources.
     */
    private $resources = array(
        'Agent',
        'AgentConnection',
        'AgentHost',
        'AgentHostInfo',
        'AgentTarget',
        'AgentToken',
        'Alarm',
        'Changelog',
        'Check',
        'CheckType',
        'Entity',
        'Metric',
        'Notification',
        'NotificationHistory',
        'NotificationPlan',
        'NotificationType',
        'View',
        'Zone'
    );

    public function __construct(OpenStack $connection, $serviceName, $serviceRegion, $urlType)
    {
        parent::__construct(
            $connection, 'rax:monitor', $serviceName, $serviceRegion, $urlType
        );
    }

    public function getEntities()
    {
        return $this->getService()->resourceList('Entity');
    }

    public function getEntity($id = null)
    {
        return $this->getService()->resource('Entity', $id);
    }

    public function getCheckTypes()
    {
        return $this->getService()->resourceList('CheckType');
    }

    public function getCheckType($id = null)
    {
        return $this->getService()->resource('CheckType', $id);
    }

}