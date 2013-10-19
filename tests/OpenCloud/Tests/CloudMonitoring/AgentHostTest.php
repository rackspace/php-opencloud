<?php

namespace OpenCloud\Tests\CloudMonitoring;

use OpenCloud\Tests\OpenCloudTestCase;

class AgentHostTest extends OpenCloudTestCase
{
    const AGENT_ID = 'someId';

    public function __construct()
    {
        $this->service = $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW', 'publicURL');
        
        // Set up parent resource
        $agent = $this->service->resource('Agent');
        $agent->refresh(self::AGENT_ID);

        // Get main resource
        $this->resource = $this->service->resource('AgentHost');
        $this->resource->setParent($agent);
    }
    
    public function test__construct()
    {
        $this->getClient()->cloudMonitoring('cloudMonitoring', 'DFW');
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentHost',
            $this->resource
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFails()
    {
        $this->resource->Create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFails()
    {
        $this->resource->Update();
    }
    
    public function testCollection()
    {
        $this->assertInstanceOf(
            'OpenCloud\\Common\\Collection',
            $this->resource->info('cpus')
        );
    }

    public function testCPU()
    {
        $list = $this->resource->info('cpus');
        while ($info = $list->Next()) {
            $this->assertObjectHasAttribute('name', $info);
            $this->assertObjectHasAttribute('vendor', $info);
            $this->assertObjectHasAttribute('model', $info);
        }
    }
    
    public function testDisks()
    {
        $list = $this->resource->info('disks');
        while ($info = $list->Next()) {
            $this->assertObjectHasAttribute('read_bytes', $info);
            $this->assertObjectHasAttribute('reads', $info);
            $this->assertObjectHasAttribute('rtime', $info);
        }
    }

    public function testFilesystems()
    {
        $list = $this->resource->info('filesystems');
        while ($info = $list->Next()) {
            $this->assertObjectHasAttribute('dir_name', $info);
            $this->assertObjectHasAttribute('dev_name', $info);
            $this->assertObjectHasAttribute('free_files', $info);
        }
    }

    public function testNetworkInterfaces()
    {
        $list = $this->resource->info('network_interfaces');
        while ($info = $list->Next()) {
            $this->assertObjectHasAttribute('name', $info);
            $this->assertObjectHasAttribute('type', $info);
            $this->assertObjectHasAttribute('netmask', $info);
        }
    }
    
 
    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\AgentException
     */
    public function testInfoFailsWithIncorrectType()
    {
        $this->resource->info('foobar');
    }
    
}