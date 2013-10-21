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

namespace OpenCloud\Tests\Compute;

use OpenCloud\Compute\Resource\Network;

class NetworkTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;
    private $net;

    public function __construct()
    {
        $this->service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW', 'publicURL');
        $this->network = $this->service->network(RAX_PUBLIC);
    }

    public function test__construct()
    {
        $this->assertEquals(RAX_PUBLIC, $this->network->id);
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Network', $this->network);
    }

    public function test_Create()
    {
        $net = $this->service->network();
        $net->create(array('label' => 'foo', 'cidr' => 'bar'));
        $this->assertEquals('foo', $net->label);
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
        $network = new Network($this->service, RAX_PRIVATE);
        $network->delete();
    }
    
    public function test_Deletes_With_Custom_Ip()
    {
        $network = $this->service->network('0.0.0.0');
        $network->delete();
    }
    
}
