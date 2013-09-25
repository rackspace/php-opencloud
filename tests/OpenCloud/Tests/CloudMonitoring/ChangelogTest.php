<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class ChangelogTest extends OpenCloudTestCase
{
    
    const NT_ID = 'webhook';
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('Changelog');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Changelog',
            $this->resource
        );
    }
    
    public function testResourceUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/changelogs/alarms',
            $this->resource->url()
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
        
        $first = $this->resource->listAll()->first();
        
        $this->assertEquals('4c5e28f0-0b3f-11e1-860d-c55c4705a286', $first->getId());
        $this->assertEquals('enPhid7noo', $first->getEntityId());
    }
    
}