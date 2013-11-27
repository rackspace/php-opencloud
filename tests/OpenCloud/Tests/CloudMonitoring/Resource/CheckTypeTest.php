<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class CheckTypeTest extends CloudMonitoringTestCase
{
    /**
     * @mockFile CheckType_List
     */
    public function testListAllCheckTypesClass()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->getCheckTypes());
    }

    /**
     * @mockFile CheckType
     */
    public function testGetCheckType()
    {
        $type = $this->service->getCheckType('remote.dns');

        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\CheckType', $type);

        $this->assertEquals('remote.dns', $type->getId());
        $this->assertEquals('remote', $type->getType());
    }

}