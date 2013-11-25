<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class NotificationTypeTest extends CloudMonitoringTestCase
{
    
    const NT_ID = 'webhook';
    
    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $response = new Response(200, array('Content-Type' => 'application/json'), '{"id":"webhook","fields":[{"name":"url","description":"An HTTP or HTTPS URL to POST to","optional":false}]}');
        $this->addMockSubscriber($response);

        $this->resource = $this->service->getNotificationType(self::NT_ID);
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
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/notification_types/webhook',
            (string) $this->resource->getUrl()
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
     * @mockFile NotificationType_List
     */
    public function testListAll()
    {
        $list = $this->service->getNotificationTypes();

        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $list
        );
        
        $first = $list->first();
        
        $this->assertEquals('webhook', $first->getId());
        $fields = $first->getFields();
        $this->assertEquals('An HTTP or HTTPS URL to POST to', $fields[0]->description);
    }

    public function testGet()
    {
        $fields = $this->resource->getFields();
        $this->assertEquals('url', $fields[0]->name);
        $this->assertFalse($fields[0]->optional);
    }
    
}