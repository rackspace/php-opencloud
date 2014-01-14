<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Database;

use OpenCloud\Tests\OpenCloudTestCase;

class DatabaseTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $instance;

    protected $mockPath = 'Database';

    public function setupObjects()
    {
        $this->service = $this->getClient()->databaseService();

        $this->addMockSubscriber($this->getTestFilePath('Instance'));
        $this->instance = $this->service->instance('foo');
    }
}