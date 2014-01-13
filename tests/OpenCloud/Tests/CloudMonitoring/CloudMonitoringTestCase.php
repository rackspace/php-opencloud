<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class CloudMonitoringTestCase extends OpenCloudTestCase
{
    const ENTITY_ID = 'en5hw56rAh';

    protected $service;
    protected $entity;

    protected $mockPath = 'CloudMonitoring';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $this->addMockSubscriber($this->getTestFilePath('Entity'));
        $this->entity = $this->service->getEntity(self::ENTITY_ID);
    }
}