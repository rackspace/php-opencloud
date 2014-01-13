<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\DNS;

use OpenCloud\Tests\OpenCloudTestCase;

class DnsTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $domain;
    protected $record;

    protected $mockPath = 'DNS';

    public function setupObjects()
    {
        $this->service = $this->getClient()->dnsService();

        $this->addMockSubscriber($this->getTestFilePath('Domain'));
        $this->domain = $this->service->domain('foo');

        $this->record = $this->service->ptrRecord();
    }
}