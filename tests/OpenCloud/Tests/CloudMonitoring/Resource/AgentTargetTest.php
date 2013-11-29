<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class AgentTargetTest extends CloudMonitoringTestCase
{
    public function setupObjects()
    {
        parent::setupObjects();

        $this->resource = $this->service->resource('AgentTarget', null, $this->entity);
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

    /**
     * @mockFile Agent_Target_List
     */
    public function testCollectionContent()
    {
        $this->resource->setType('agent.filesystem');
        $this->assertEquals('agent.filesystem', $this->resource->getType());
        
        $targetArray = $this->resource->listAll();

        $this->assertTrue($targetArray->valueExists('/'));
        $this->assertTrue($targetArray->valueExists('/sys/kernel/debug'));
        $this->assertTrue($targetArray->valueExists('/var/lock'));
    }
    
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testSettingIncorrectTypeFails()
    {
        $this->resource->setType('foobar');
    }
    
}