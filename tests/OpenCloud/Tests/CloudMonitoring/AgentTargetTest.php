<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class AgentTargetTest extends OpenCloudTestCase
{
    const ENTITY_ID = 'enAAAAA';

    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        
        // Set up parent resource
        $agent = $this->service->resource('Entity');
        $agent->populate(self::ENTITY_ID);

        // Get main resource
        $this->resource = $this->service->resource('AgentTarget');
        $this->resource->setParent($agent);
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentTarget',
            $this->resource
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFails()
    {
        $this->resource->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFails()
    {
        $this->resource->update();
    }

    public function testCollectionContent()
    {
        $this->resource->setType('agent.filesystem');
        $this->assertEquals('agent.filesystem', $this->resource->getType());
        
        $targetArray = $this->resource->listAll();

        $this->assertContains('/', $targetArray);
        $this->assertContains('/sys/kernel/debug', $targetArray);
        $this->assertContains('/var/lock', $targetArray);
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testSettingIncorrectTypeFails()
    {
        $this->resource->setType('foobar');
    }
    
}