<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

/**
 * Description of CloudMonitoring
 * 
 * @link 
 */
class CloudMonitoring extends AbstractUnit implements UnitInterface
{
    const ENTITY_LABEL = 'test_entity';
    const CHECK_LABEL = 'website_check';

    const DEFAULT_CHECK_TYPE = 'remote.dns';

    private $entity;

    public function setupService()
    {
        return $this->getConnection()->computeService('cloudMonitoring', Utils::getRegion());
    }

    public function main()
    {
        $this->doEntityBlock();
    }

    public function teardown()
    {
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

        $this->stepInfo('List entities');

        $entities = $this->getService()->getEntities();
        while ($entity = $entities->next()) {
            $this->stepInfo('Entity: %s', $entity->getLabel());
        }
    }

    public function doEntityTeardown()
    {
        $entities = $this->getService()->getEntities();
        while ($entity = $entities->next()) {
            if ($this->shouldDelete($entity->getLabel())) {
                $entity->delete();
            }
        }
    }

    public function doCheckBlock()
    {
        $this->step('Checks');

        /*** CHECK TYPES ***/

        $step1 = $this->stepInfo('List check types');
        $types = $this->getService()->getCheckTypes();
        while ($type = $types->next()) {
            $step1->stepInfo('Check type: [%s] %s', $type->getId(), $type->getName());
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
            'period' => 100,
            'timeout' => 30,
            'target_alias' => 'default',
            'label'  => $this->prepend(self::CHECK_LABEL)
        );

        $this->stepInfo('Testing check parameters');
        $check->test($params);

        $this->stepInfo('Create check for entity ID %s', $this->entity->getId());
        $check->create($params);

        $debug = $check->testExisting(true);
        $this->stepInfo('Test existing check: %s', print_r($debug, true));

        $step2 = $this->stepInfo('List checks');
        $checks = $this->entity->getChecks();
        while ($check = $checks->next()) {
            $step2->stepInfo($check->getLabel());
        }

        $check->update(array('period' => 200));
    }

    public function doMetricsBlock()
    {

    }

    public function doNotificationsBlock()
    {
        // notification plans

        // notifications

        // notification types

        // alarm notification history
    }

    public function doMonitoringZonesBlock()
    {

    }

    public function doOtherBlock()
    {
        // changelog

        // views
    }

    public function doAgentBlock()
    {
        // agents

        // agent token

        // agent host information

        // agent targets
    }
}