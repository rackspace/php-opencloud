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

namespace OpenCloud\Tests\Common;

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;

    public function __construct()
    {
        $this->service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW');
    }

    public function testExtensions()
    {
        $ext = $this->service->Extensions();
        $this->assertTrue(is_array($ext));
    }

    public function testRegion()
    {
        $this->assertEquals('DFW', $this->service->region());
    }

    public function testName()
    {
        $this->assertEquals('cloudServersOpenStack', $this->service->name());
    } 
        
}
