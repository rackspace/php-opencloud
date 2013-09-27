<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class NotificationTypeTest extends OpenCloudTestCase
{
    
    const NT_ID = 'webhook';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('NotificationType');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\NotificationType',
            $this->resource
        );
    }
    
    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/notification_types',
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
    
    public function testListAll()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
        
        $first = $this->resource->listAll()->First();
        
        $this->assertEquals('webhook', $first->getId());
        $fields = $first->getFields();
        $this->assertEquals('An HTTP or HTTPS URL to POST to', $fields[0]->description);
    }
    
    public function testGet()
    {
        $this->resource->refresh(self::NT_ID);

        $fields = $this->resource->getFields();
        $this->assertEquals('url', $fields[0]->name);
        $this->assertFalse($fields[0]->optional);
    }
    
}