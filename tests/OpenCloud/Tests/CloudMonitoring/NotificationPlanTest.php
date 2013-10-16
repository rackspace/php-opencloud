<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class NotificationPlanTest extends OpenCloudTestCase
{
    
    const NP_ID = 'npAAAA';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
           
        $this->resource = $this->service->resource('NotificationPlan');
    }
    
    public function testNPClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\NotificationPlan',
            $this->resource
        );
    }
    
    public function testNPUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/notification_plans',
            $this->resource->Url()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithoutParams()
    {
        $this->resource->Create();
    }
    
    public function testListAllClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
    }
    
    public function testListAllProperties()
    {
        $list = $this->resource->listAll();
        $first = $list->first();
        $this->assertObjectHasAttribute('label', $first);
    }
    
    public function testGet()
    {
        $this->resource->refresh(self::NP_ID);
        $this->assertEquals('Notification Plan 1', $this->resource->getLabel());
    }
    
}