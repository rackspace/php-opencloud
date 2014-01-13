<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

use OpenCloud\Tests\OpenCloudTestCase;

class ComputeTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $server;

    protected $mockPath = 'Compute';

    public function setupObjects()
    {
        $this->addMockSubscriber($this->getTestFilePath('Extensions'));
        $this->service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW', 'publicURL');

        $this->addMockSubscriber($this->getTestFilePath('Server'));
        $this->server = $this->service->server('SERVER-ID');
    }
}