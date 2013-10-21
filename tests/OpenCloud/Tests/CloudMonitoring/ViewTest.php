<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class ViewTest extends OpenCloudTestCase
{
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL'); 
        $this->resource = $this->service->resource('View');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\View',
            $this->resource
        );
    }
    
    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/views/overview',
            $this->resource->Url()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailWithNoParams()
    {
        $this->resource->create();
    }
    
    public function testListAll()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
        
        $list = $this->resource->listAll();
        
        $first = $list->first();

        $this->assertEquals('enBBBBIPV4', $first->getEntity()->getId());
        
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Entity',
            $first->getEntity()        
        );
    }
        
}