<?php

namespace OpenCloud\Tests\CloudMonitoring;

use PHPUnit_Framework_TestCase;
use OpenCloud\CloudMonitoring\Service;
use OpenCloud\CloudMonitoring\Exception;
use OpenCloud\Common\Collection;

class ViewTest extends PHPUnit_Framework_TestCase
{
    
    public function __construct()
    {
        $this->connection = new FakeConnection('http://example.com', 'SECRET');

        $this->service = new Service(
            $this->connection,
            'cloudMonitoring',
            array('LON'),
            'publicURL'
        );
        
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
        $hostnames = $this->service->getHostnames();
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/views/overview',
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
    
    public function testListAll()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
        
        $list = $this->resource->listAll();
        
        $first = $list->First();

        $this->assertEquals('enBBBBIPV4', $first->entity->id);
        
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Entity',
            $first->entity        
        );
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\ServiceException
     */
    public function testServiceResource()
    {
        $this->assertNotEmpty($this->service->getResources());
        
        $this->service->resource('FooBar');
    }
    
}