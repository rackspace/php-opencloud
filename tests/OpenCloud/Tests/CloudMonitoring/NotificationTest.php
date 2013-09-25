<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class NotificationTest extends OpenCloudTestCase
{
    
    const NOTIFICATION_ID = 'ntAAAA';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('Notification');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Notification',
            $this->resource
        );
    }
    
    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/notifications',
            $this->resource->Url()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailWithNoParams()
    {
        $this->resource->Create();
    }
    
    public function testCreateTest()
    {
        $params = array(
            'label' => 'Test notification',
            'type'  => 'webhook',
            'details' => array(
                'url' => 'http://test.com'
            )
        );
        
        $response = $this->resource->test($params);

        $this->assertEquals('success', $response->status);
    }
    
    public function testExistingTest()
    {
        $this->resource->refresh(self::NOTIFICATION_ID);
        $response = $this->resource->testExisting();
        $this->assertEquals('success', $response->status);
    }
    
    public function testGet()
    {
        $this->resource->refresh(self::NOTIFICATION_ID);
        
        $this->assertEquals('my webhook #1', $this->resource->getLabel());
    }
    
}