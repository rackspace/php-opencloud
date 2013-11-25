<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Compute\Constants\Network as NetworkConst;
use OpenCloud\Compute\Resource\Network;
use OpenCloud\Tests\Compute\ComputeTestCase;

class NetworkTest extends ComputeTestCase
{

    public function __construct()
    {
        parent::setupObjects();
        $this->addMockSubscriber($this->makeResponse('{"network":{"id":"public","ip":[{"version":4,"addr":"67.23.10.132"},{"version":6,"addr":"::babe:67.23.10.132"},{"version":4,"addr":"67.23.10.131"},{"version":6,"addr":"::babe:4317:0A83"}]}}'));
        $this->network = $this->service->network(NetworkConst::RAX_PUBLIC);
    }

    public function test__construct()
    {
        $this->assertEquals(NetworkConst::RAX_PUBLIC, $this->network->id);
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Network', $this->network);
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
    
}
