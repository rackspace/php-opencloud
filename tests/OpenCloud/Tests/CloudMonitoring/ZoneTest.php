<?php

namespace OpenCloud\Tests\CloudMonitoring;

use PHPUnit_Framework_TestCase;
use OpenCloud\Tests\OpenCloudTestCase;

class ZoneTest extends OpenCloudTestCase
{
    
    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('zone');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Zone',
            $this->resource
        );
    }
    
    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/monitoring_zones',
            $this->resource->Url()
        );
    }
    
    public function testCollection()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
    }
    
    public function testCollectionContent()
    {
        $list = $this->resource->listAll();
        $first = $list->First();
        
        $this->assertEquals('mzAAAAA', $first->getId());
        $this->assertEquals('US', $first->getCountryCode());
    }
    
    public function testGetClass()
    {
        $this->resource->refresh('mzAAAAA');
        $this->assertEquals('mzAAAAA', $this->resource->getId());
    }
    
    public function testTraceroute()
    {
        $this->resource->setId('mzAAAAA');
        $object = $this->resource->traceroute(array(
            'target' => 'http://test.com',
            'target_resolver' => 'foo'
        ));
        
        $this->assertCount(27, $object->result);
        $this->assertEquals('173.194.78.139', $object->result[26]->ip);
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\ZoneException
     */
    public function testTracerouteFailsWithoutId()
    {
        $this->resource->traceroute(array());
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\ZoneException
     */
    public function testTracerouteFailsWithoutTarget()
    {
        $this->resource->setId('mzAAAAA');
        $this->resource->traceroute(array());
    }

}