<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Utils;

/**
 * Description of CloudMonitoring
 * 
 * @link 
 */
class CloudMonitoring extends AbstractUnit implements UnitInterface
{
    const ENTITY_LABEL = 'test_entity';
    const CHECK_LABEL = 'website_check';
    const NOTIFICATION_LABEL = 'test_notification';
    const NOTIFICATION_PLAN_LABEL = 'test_notification_plan';

    const DEFAULT_CHECK_TYPE = 'remote.dns';
    const TRACEROUTE_TARGET  = 'bbc.com';

    private $entity;
    private $check;

    public function setupService()
    {
        return $this->getConnection()->cloudMonitoringService('cloudMonitoring', Utils::getRegion());
    }

    public function main()
    {
        $this->doEntityBlock();
        //$this->entity = $this->getService()->getEntity('enrWJv2inD');

        $this->doCheckBlock();
        //$this->check = $this->entity->getCheck('chw1LVCem6');

        $this->doMetricsBlock();
        $this->doNotificationsBlock();
        //$this->notificationPlan = $this->getService()->getNotificationPlan('npyooOmV8g');

        $this->doAlarmBlock();
        $this->doMonitoringZonesBlock();
        $this->doOtherBlock();

        $this->doAgentBlock();
    }

    public function teardown()
    {
        $this->doEntityTeardown();
    }

    public function doEntityBlock()
    {
        $this->step('Entities');

        $this->entity = $this->getService()->getEntity();

        $this->stepInfo('Create entity');

        $this->entity->create(array(
            'label' => $this->prepend(self::ENTITY_LABEL),
            'ip_addresses' => array(
                'default' => '173.194.116.32', // google.com
                'backup'  => '98.139.183.24'   // yahoo.com
            ),
            'metadata' => array(
                'all'  => 'kinds',
                'of'   => 'stuff',
                'can'  => 'go',
                'here' => 'null is not a valid value'
            )
        ));

        $this->stepInfo('Update entity');

        $this->entity->update(array(
            'metadata' => array(
                'foo' => 'bar'
            )
        ));

        $step = $this->stepInfo('List entities');

        $entities = $this->getService()->getEntities();
        foreach ($entities as $entity) {
            $step->stepInfo('Entity: %s', $entity->getLabel());
        }
    }

    public function doEntityTeardown()
    {
        $this->step('Deleting entities');

        $entities = $this->getService()->getEntities();

        foreach ($entities as $entity) {
            if ($this->shouldDelete($entity->getLabel())) {
                try {
                    $this->stepInfo('Deleting entity: ID [%s], label [%s]', $entity->getId(), $entity->getLabel());
                    $entity->delete();
                } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
                    $this->stepInfo('Could not delete entity: ID [%s], label [%s]', $entity->getId(), $entity->getLabel());
                }
            }
        }
    }

    public function doCheckBlock()
    {
        $this->step('Checks');

        /*** CHECK TYPES ***/

        $step1 = $this->stepInfo('List check types');
        $types = $this->getService()->getCheckTypes();
        foreach ($types as $type) {
            $step1->stepInfo('Check type: ID [%s], type [%s]', $type->getId(), $type->getType());
        }

        $this->stepInfo('Get check type');
        $checkType = $this->getService()->getCheckType(self::DEFAULT_CHECK_TYPE);


        /*** CHECKS ***/

        /**
         * On IRC, ask Cloud Monitoring team why they have an IP hash for entities when individual checks set the URL.
         * In the docs, they say checks can "reference" the IPs of their parent, but I can't see how.
         */

        $check = $this->entity->getCheck();
        $params = array(
            'type'   => 'remote.http',
            'details' => array(
                'url'    => 'http://example.com',
                'method' => 'GET'
            ),
            'monitoring_zones_poll' => array('mzlon'),
            'timeout' => 10,
            'target_alias' => 'default'
        );

        $this->stepInfo('Testing check parameters');
        $check->testParams($params);

        $params['period'] = 30;
        $params['label'] = $this->prepend(self::CHECK_LABEL);

        $this->stepInfo('Create check for entity ID %s', $this->entity->getId());
        $check->create($params);

        $debug = $check->test(true);
        $this->stepInfo('Test existing check: %s', print_r($debug, true));

        $step2 = $this->stepInfo('List checks');
        $checks = $this->entity->getChecks();
        foreach ($checks as $check) {
            $step2->stepInfo($check->getLabel());

            $finalCheck = $check;
        }

        $this->stepInfo('Updating check %s', $finalCheck->getId());
        $finalCheck->update(array('period' => 200));
        $this->check = $finalCheck;
    }

    public function doMetricsBlock()
    {
        $this->step('Metrics');

        // fetch metrics
        $step1 = $this->stepInfo('Showing metrics for check %s: ', $this->check->getId());
        $metrics = $this->check->getMetrics();
        if (!$metrics->count()) {
            $this->stepInfo('No metrics to show yet!');
            return;
        }
        foreach ($metrics as $metric) {
            $step1->stepInfo(print_r($metric, true));
        }
    }

    public function doNotificationsBlock()
    {
        /*** NOTIFICATIONS ***/
        $this->step('Notification');

        $params = array(
            'label' => $this->prepend(self::NOTIFICATION_LABEL),
            'type'  => 'webhook',
            'details' => (object) array('url' => 'http://google.com')
        );

        $this->stepInfo('Test notification');
        $this->getService()->testNotification($params);

        $this->stepInfo('Create notification');
        $this->getService()->createNotification($params);

        $step1 = $this->stepInfo('List notifications');
        $notifications = $this->getService()->getNotifications();
        foreach ($notifications as $notification) {
            $step1->stepInfo('Notification %s', $notification->getId());
            $finalNotification = $notification;
        }

        /*** NOTIFICATION PLANS ***/

        if (isset($finalNotification)) {
            $this->step('Notification plans');

            $id = $finalNotification->getId();

            // create
            $this->stepInfo('Create NP');
            $this->getService()->createNotificationPlan(array(
                'label' => $this->prepend(self::NOTIFICATION_PLAN_LABEL),
                'critical_state' => array($id),
                'warning_state'  => array($id),
                'ok_state'       => array($id),
            ));
        }

        // list
        $step2 = $this->stepInfo('List NPs');
        $plans = $this->getService()->getNotificationPlans();
        foreach ($plans as $plan) {
            $step2->stepInfo('Notification Plan %s', $plan->getId());
            $this->notificationPlan = $plan;
        }
    }

    public function doAlarmBlock()
    {
        $this->step('Alarms');

        // test alarm params
        $this->stepInfo('Test alarm');
        $this->entity->testAlarm(array(
            'check_data' => $this->check->test(),
            'criteria' => 'if (metric["duration"] >= 2) { return new AlarmStatus(OK); } return new AlarmStatus(CRITICAL);'
        ));

        // create alarm
        $this->stepInfo('Create alarm');
        $this->entity->createAlarm(array(
            'check_id' => $this->check->getId(),
            'criteria' => 'if (metric["duration"] >= 2) { return new AlarmStatus(OK); } return new AlarmStatus(CRITICAL);',
            'notification_plan_id' => $this->notificationPlan->getId()
        ));

        // list alarms
        $step = $this->stepInfo('List alarms');
        $alarms = $this->entity->getAlarms();
        foreach ($alarms as $alarm) {
            $step->stepInfo('Alarm %s', $alarm->getId());
            $finalAlarm = $alarm;
        }

        $this->step('Alarm notification history');

        if (!isset($finalAlarm)) {
            $this->stepInfo('No alarms!');
            return;
        }

        $step1 = $this->stepInfo('List recorded checks for alarm %s', $finalAlarm->getId());
        $checkIds = $finalAlarm->getRecordedChecks();

        if (!is_array($checkIds)) {
            return;
        }

        foreach ($checkIds as $checkId) {
            $step1->stepInfo('Check recorded: %s', $checkId);
        }

        $step2 = $this->stepInfo('List notification history for check %s on alarm %s', $checkId, $finalAlarm->getId());
        $history = $finalAlarm->getNotificationHistoryForCheck($checkId);

        foreach ($history as $historyItem) {
            $step2->stepInfo('History item: ID [%s] with status [%s]', $historyItem->getId(), $historyItem->getStatus());
        }
    }

    public function doMonitoringZonesBlock()
    {
        $this->step('Monitoring zones');

        // list zones
        $step = $this->stepInfo('List zones');
        $zones = $this->getService()->getMonitoringZones();
        foreach ($zones as $zone) {
            $zoneId = $zone->getId();
            $step->stepInfo('Monitoring zone: ID [%s], label [%s]', $zoneId, $zone->getLabel());
        }

        // get zone
        $this->stepInfo('Get zone');
        $zone = $this->getService()->getMonitoringZone($zoneId);

        // perform traceroute
        $trace = $zone->traceroute(array('target' => self::TRACEROUTE_TARGET,'target_resolver' => 'IPv4'));
        $this->stepInfo('Traceroute: %s', print_r($trace, true));
    }

    public function doOtherBlock()
    {
        // changelog
        $this->step('Changelog');

        //$step = $this->stepInfo('List 1st key of changelog for entity %s', $this->entity->getId());
        //$changelog = $this->getService()->getChangelog($this->entity->getId());
        //$step->stepInfo(print_r($changelog[0], true));

        // views
        $this->step('Views');

        $step = $this->stepInfo('List 1st key of views');
        $views = $this->getService()->getViews();
        $step->stepInfo(print_r($views->first(), true));
    }

    public function doAgentBlock()
    {
        // agents

        // agent token

        // agent host information

        // agent targets
    }
}