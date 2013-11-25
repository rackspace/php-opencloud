<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class ViewTest extends CloudMonitoringTestCase
{
    
    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $this->addMockSubscriber($this->getTestFilePath('View'));
        $this->resource = $this->service->getViews();
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->resource);

        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\View', $this->resource->first());
    }
    
    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/views/overview',
            (string) $this->resource->first()->getUrl()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailWithNoParams()
    {
        $this->resource->first()->create();
    }
    
    public function test_Values()
    {
        $item = $this->resource->first();

        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Entity', $item->getEntity());

        $this->assertInstanceOf(self::COLLECTION_CLASS, $item->getAlarms());
        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Alarm', $item->getAlarms()->first());

        $this->assertInstanceOf(self::COLLECTION_CLASS, $item->getChecks());
        $this->assertInstanceOf('OpenCloud\CloudMonitoring\Resource\Check', $item->getChecks()->first());
    }
        
}