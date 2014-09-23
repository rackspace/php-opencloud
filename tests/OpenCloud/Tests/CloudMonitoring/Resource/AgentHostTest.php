<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
    public function testCPU()
    {
        $list = $this->host->info('cpus');

        $this->isCollection($list);

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

        $this->isCollection($list);

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

        $this->isCollection($list);

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('dir_name', $info);
            $this->assertObjectHasAttribute('dev_name', $info);
            $this->assertObjectHasAttribute('free_files', $info);
        }
    }

    /**
     * @mockFile Agent_Host_Filesystems
     */
    public function testMemory()
    {
        $list = $this->host->info('memory');

        $this->isCollection($list);

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('actual_free', $info);
            $this->assertObjectHasAttribute('actual_used', $info);
            $this->assertObjectHasAttribute('free', $info);
        }
    }

    /**
     * @mockFile Agent_Host_NetworkInterfaces
     */
    public function testNetworkInterfaces()
    {
        $list = $this->host->info('network_interfaces');

        $this->isCollection($list);

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('name', $info);
            $this->assertObjectHasAttribute('type', $info);
            $this->assertObjectHasAttribute('netmask', $info);
        }
    }

    /**
     * @mockFile Agent_Host_Processes
     */
    public function testProcesses()
    {
        $list = $this->host->info('processes');

        $this->isCollection($list);

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('pid', $info);
            $this->assertObjectHasAttribute('memory_size', $info);
            $this->assertObjectHasAttribute('time_start_time', $info);
        }
    }

    /**
     * @mockFile Agent_Host_System
     */
    public function testSystem()
    {
        $list = $this->host->info('system');

        $this->isCollection($list);

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('name', $info);
            $this->assertObjectHasAttribute('arch', $info);
            $this->assertObjectHasAttribute('version', $info);
        }
    }

    /**
     * @mockFile Agent_Host_Who
     */
    public function testWho()
    {
        $list = $this->host->info('who');

        $this->isCollection($list);

        foreach ($list as $info) {
            $this->assertObjectHasAttribute('user', $info);
            $this->assertObjectHasAttribute('device', $info);
            $this->assertObjectHasAttribute('time', $info);
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
