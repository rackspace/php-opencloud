<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Volume;

use OpenCloud\Tests\OpenCloudTestCase;

class VolumeTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $volume;

    protected $mockPath = 'Volume';

    public function setupObjects()
    {
        $this->service = $this->getClient()->volumeService();

        $this->addMockSubscriber($this->getTestFilePath('Volume'));
        $this->volume = $this->service->volume('foo');
    }
} 