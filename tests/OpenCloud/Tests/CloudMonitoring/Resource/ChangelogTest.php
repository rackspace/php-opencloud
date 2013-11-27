<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class ChangelogTest extends CloudMonitoringTestCase
{

    const NT_ID = 'webhook';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();
        $this->addMockSubscriber($this->getTestFilePath('Changelogs'));
        $this->resource = $this->service->getChangelog()->first();
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Changelog',
            $this->resource
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailWithNoParams()
    {
        $this->resource->create();
    }

    /**
     * @mockFile Changelogs
     */
    public function testListAll()
    {
        $list = $this->service->getChangelog();

        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);

        $first = $list->first();

        $this->assertEquals('4c5e28f0-0b3f-11e1-860d-c55c4705a286', $first->getId());
        $this->assertEquals('enPhid7noo', $first->getEntityId());
    }

}