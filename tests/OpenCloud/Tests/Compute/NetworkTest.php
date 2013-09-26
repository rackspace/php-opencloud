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
        $this->service = $this->getClient()->compute('cloudServersOpenStack', 'DFW', 'publicURL');
        $this->net = new Network($this->service, RAX_PUBLIC);
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertEquals(RAX_PUBLIC, $this->net->id);
        $net = $this->service->Network();
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Network', $net);
    }

    public function testCreate()
    {
        $net = $this->service->network();
        $net->Create(array('label' => 'foo', 'cidr' => 'bar'));
        $this->assertEquals('foo', $net->label);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\NetworkUpdateError
     */
    public function testUpdate()
    {
        $this->net->update();
    }

    public function testDelete()
    {
        $net = $this->service->Network();
        $net->id = 'foobar';
        $resp = $net->delete();
        $this->assertEquals(202, $resp->HttpStatus());
    }

    public function testName()
    {
        $this->assertEquals('public', $this->net->Name());
    }

    public function testConstruct()
    {
        $network = new Network($this->service, RAX_PUBLIC);
        
    }
    
    /**
     * @expectedException \OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDeleteFailsWithIncorrectIp()
    {
        $network = new Network($this->service, RAX_PRIVATE);
        $network->delete();
    }
    
}
