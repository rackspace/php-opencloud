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

namespace OpenCloud\Tests\ObjectStore;

class ObjectStoreTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;

    public function __construct()
    {
        $this->service = $this->getClient()->objectStore('cloudFiles', 'DFW', 'publicURL');
    }

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\ObjectStore\Service', $this->service);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID', 
            $this->service->url()
        );
    }

    public function testContainer()
    {
        $obj = $this->service->container();
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $obj);
    }

    public function testContainerList()
    {
        $clist = $this->service->containerList();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $clist);
    }
    
    public function testCDN()
    {
        $this->assertInstanceOf('OpenCloud\ObjectStore\CDNService', $this->service->CDN());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnError
     */
    public function testCDNCDN()
    {
        $this->assertFalse(get_class($this->service->CDN()->CDN()));
    }

}
