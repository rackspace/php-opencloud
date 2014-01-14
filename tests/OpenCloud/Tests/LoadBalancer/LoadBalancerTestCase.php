<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\LoadBalancer;

use OpenCloud\Tests\OpenCloudTestCase;

class LoadBalancerTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $loadBalancer;

    protected $mockPath = 'LoadBalancer';

    public function setupObjects()
    {
        $this->service = $this->getClient()->loadBalancerService();

        $this->addMockSubscriber($this->getTestFilePath('LoadBalancer'));
        $this->loadBalancer = $this->service->loadBalancer('foo');
    }
}