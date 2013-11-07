<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class CheckTypeTest extends OpenCloudTestCase
{
	
	public function __construct()
	{
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('CheckType');
	}

    public function testCheckTypeClass()
    {
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\CheckType', $this->resource);
    }

    public function testListAllCheckTypesClass()
    {
        $this->assertInstanceOf('OpenCloud\\Common\\Collection', $this->resource->listAll());
    }

    public function testListAllCheckTypesHasRightCount()
    {
        $response = $this->resource->listAll();
        $this->assertInstanceOf('OpenCloud\\CloudMonitoring\\Resource\\CheckType', $response->first());
    }

    public function testGetCheckType()
    {
        $this->resource->populate('remote.dns');

        $this->assertEquals('remote.dns', $this->resource->getId());
        $this->assertEquals('remote', $this->resource->getType());
    }

}