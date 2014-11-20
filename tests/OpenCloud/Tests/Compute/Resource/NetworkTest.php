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

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version   1.0.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Compute\Constants\Network as NetworkConst;
use OpenCloud\Compute\Resource\Network;
use OpenCloud\OpenStack;
use OpenCloud\Tests\Compute\ComputeTestCase;
use OpenCloud\Tests\MockSubscriber;

class NetworkTest extends ComputeTestCase
{
    public function setupObjects()
    {
        parent::setupObjects();
        $this->addMockSubscriber($this->makeResponse('{"network":{"id":"public","ip":[{"version":4,"addr":"67.23.10.132"},{"version":6,"addr":"::babe:67.23.10.132"},{"version":4,"addr":"67.23.10.131"},{"version":6,"addr":"::babe:4317:0A83"}]}}'));
        $this->network = $this->service->network(NetworkConst::RAX_PUBLIC);
    }

    public function test__construct()
    {
        $this->assertEquals(NetworkConst::RAX_PUBLIC, $this->network->id);
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Network', $this->network);
        $this->assertInstanceOf('OpenCloud\Networking\Resource\NetworkInterface', $this->network);
    }

    public function test_Create()
    {
        $this->addMockSubscriber($this->makeResponse());
        $net = $this->service->network();
        $net->create(array('label' => 'foo', 'cidr' => 'bar'));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\NetworkUpdateError
     */
    public function test_Update()
    {
        $this->network->update();
    }

    public function test_Name()
    {
        $this->assertEquals('public', $this->network->name());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function test_Delete_Fails_With_Incorrect_Ip()
    {
        $network = new Network($this->service, NetworkConst::RAX_PRIVATE);
        $network->delete();
    }

    public function test_Deletes_With_Custom_Ip()
    {
        $this->addMockSubscriber($this->makeResponse());

        $network = $this->service->network('0.0.0.0');
        $network->delete();
    }

    public function test_Paths()
    {
        $this->assertStringEndsWith('os-networksv2', (string)$this->service->network()->getUrl());

        $client = new OpenStack('http://identity.example.com/v2', array('username' => 'foo', 'password' => 'bar'));

        $response = $this->getTestFilePath('Auth_OpenStack', '.');
        $client->addSubscriber(new MockSubscriber(array($response)));

        $service = $client->computeService('compute', 'RegionOne', 'publicURL');
        $this->assertStringEndsWith('os-networks', (string)$service->network()->getUrl());
    }
}
