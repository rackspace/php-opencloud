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

use OpenCloud\Common\Service\AbstractService;

class Service extends AbstractService
{
    const DEFAULT_TYPE = 'rax:monitor';
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

    public function getEntities()
    {
        return $this->resourceList('Entity');
    }

    public function getEntity($id = null)
    {
        return $this->resource('Entity', $id);
    }

    public function getCheckTypes()
    {
        return $this->resourceList('CheckType');
    }

    public function getCheckType($id = null)
    {
        return $this->resource('CheckType', $id);
    }

    public function createNotification(array $params)
    {
        return $this->getNotification($params)->create();
    }

    public function testNotification(array $params)
    {
        return $this->getNotification()->testParams($params);
    }

    public function getNotification($id = null)
    {
        return $this->resource('Notification', $id);
    }

    public function getNotifications()
    {
        return $this->resourceList('Notification');
    }

    public function createNotificationPlan(array $params)
    {
        return $this->getNotificationPlan()->create($params);
    }

    public function getNotificationPlan($id = null)
    {
        return $this->resource('NotificationPlan', $id);
    }

    public function getNotificationPlans()
    {
        return $this->resourceList('NotificationPlan');
    }

}