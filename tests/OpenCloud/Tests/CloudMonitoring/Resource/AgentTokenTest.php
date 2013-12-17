<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\OpenCloudTestCase;

class AgentTokenTest extends OpenCloudTestCase
{

    const TOKEN_ID = 'someId';

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW', 'publicURL');
        $this->resource = $this->service->resource('AgentToken');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Agenttoken',
            $this->resource
        );
    }
    
    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/agent_tokens',
            (string) $this->resource->getUrl()
        );
    }
    
    public function testCollection()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->resource->listAll());
    }
    
    public function testGet()
    {
        $this->resource->refresh(self::TOKEN_ID);
        
        $this->assertEquals($this->resource->getId(), self::TOKEN_ID);
    }
    
}