<?php

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class AgentHostTest extends CloudMonitoringTestCase
{
    const AGENT_ID = '1d29b4f9-87ca-4b22-b5a2-4e40a07bf068';

    private $agent;
    private $host;

    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $this->addMockSubscriber($this->getTestFilePath('Agent'));
        $this->agent = $this->service->resource('Agent', self::AGENT_ID);
        $this->host = $this->service->resource('AgentHost', null, $this->agent);
    }
    
    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\AgentHost',
            $this->host
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFails()
    {
        $this->host->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFails()
    {
        $this->host->update();
    }

    /**
     * @mockFile Agent_Host_CPU
     */
    public function testCollection()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->host->info('cpus')
        );
    }

    /**
     * @mockFile Agent_Host_CPU
     */
    public function testCPU()
    {
        $list = $this->host->info('cpus');

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('name', $info);
            $this->assertObjectHasAttribute('vendor', $info);
            $this->assertObjectHasAttribute('model', $info);
        }
    }

    /**
     * @mockFile Agent_Host_Disks
     */
    public function testDisks()
    {
        $list = $this->host->info('disks');
        foreach ($list as $info) {
            $this->assertObjectHasAttribute('read_bytes', $info);
            $this->assertObjectHasAttribute('reads', $info);
            $this->assertObjectHasAttribute('rtime', $info);
        }
    }

    /**
     * @mockFile Agent_Host_Filesystems
     */
    public function testFilesystems()
    {
        $list = $this->host->info('filesystems');
        foreach ($list as $info) {
            $this->assertObjectHasAttribute('dir_name', $info);
            $this->assertObjectHasAttribute('dev_name', $info);
            $this->assertObjectHasAttribute('free_files', $info);
        }
    }

    /**
     * @mockFile Agent_Host_NetworkInterfaces
     */
    public function testNetworkInterfaces()
    {
        $list = $this->host->info('network_interfaces');
        foreach ($list as $info) {
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
        $this->host->info('foobar');
    }
    
}