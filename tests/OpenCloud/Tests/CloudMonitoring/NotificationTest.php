<?php

namespace OpenCloud\Tests\CloudMonitoring;

use PHPUnit_Framework_TestCase;
use OpenCloud\CloudMonitoring\Service;
use OpenCloud\CloudMonitoring\Exception;
use OpenCloud\Common\Collection;

class NotificationTest extends PHPUnit_Framework_TestCase
{
    
    const NOTIFICATION_ID = 'ntAAAA';
    
    public function __construct()
    {
        $this->connection = new FakeConnection('http://example.com', 'SECRET');

        $this->service = new Service(
            $this->connection,
            'cloudMonitoring',
            array('LON'),
            'publicURL'
        );
        
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
        $hostnames = $this->service->getHostnames();
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/notifications',
            $hostnames[0] . $this->resource->Url()
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
        
        $this->assertEquals('my webhook #1', $this->resource->label);
    }
    
}