<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class AgentTest extends OpenCloudTestCase
{

    const AGENT_ID      = '00-agent.example.com';
    const CONNECTION_ID = 'cntl4qsIbA';

    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('Agent');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Agent',
            $this->resource
        );
    }
    
    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/TENANT-ID/agents',
            $this->resource->url()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithNoParams()
    {
        $this->resource->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFailsWithNoParams()
    {
        $this->resource->update();
    }
    
    public function testCollection()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->listAll()
        );
    }
    
    public function testGet()
    {
        $this->resource->refresh(self::AGENT_ID);
        
        $this->assertEquals($this->resource->getId(), self::AGENT_ID);
        $this->assertEquals($this->resource->getLastConnected(), 1334685407);
    }
    
    public function testGetConnections()
    {  
        $this->resource->setId(self::AGENT_ID);
        $list = $this->resource->getConnections();
        
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $list
        );
        
        $first = $list->first();
        
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentConnection',
            $first
        );
        
        $this->assertEquals('cntl4qsIbA', $first->getId());
        $this->assertEquals('0b49b96d-24c9-45ca-c585-4040707f4880', $first->getGuid());
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testGetConnectionsFailsWithoutId()
    {
        $this->resource->setId(null);
        $this->resource->getConnections();
    }
    
    public function testGetConnection()
    {
        $this->resource->setId(self::AGENT_ID);
        $connection = $this->resource->getConnection(self::CONNECTION_ID);
        
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentConnection',
            $connection
        );
        
        $this->assertEquals('0.1.2.16', $connection->getBundleVersion());
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testGetConnectionFailsWithoutId()
    {
        $this->resource->setId(null);
        $this->resource->getConnection(null);
    }
    
}