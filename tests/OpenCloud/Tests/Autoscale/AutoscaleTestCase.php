<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Autoscale;

use OpenCloud\Tests\OpenCloudTestCase;

class AutoscaleTestCase extends OpenCloudTestCase
{
    const GROUP_ID   = '6742e741-cab6-42ff-abe5-458150afc9b1';
    const POLICY_ID  = 'policyId';
    const WEBHOOK_ID = '23037efb-53a9-4ae5-bc33-e89a56b501b6';

    const CONFIG_CLASS = 'OpenCloud\Autoscale\Resource\GroupConfiguration';
    const GROUP_CLASS  = 'OpenCloud\Autoscale\Resource\Group';

    protected $service;
    protected $group;
    protected $policy;

    protected $mockPath = 'Autoscale';

    public function setupObjects()
    {
        $this->service = $this->getClient()->autoscaleService('autoscale');

        $this->addMockSubscriber($this->getTestFilePath('Group'));
        $this->group = $this->service->group(self::GROUP_ID);
    }
}