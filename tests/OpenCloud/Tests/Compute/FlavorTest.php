<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

use PHPUnit_Framework_TestCase;
use OpenCloud\Compute\Service;
use OpenCloud\Tests\Autoscale\FakeConnection;

class FlavorTest extends PHPUnit_Framework_TestCase
{

    const FLAVOR_ID = '';

    private $service;
    private $resource;

    public function __construct()
    {
        $connection = new FakeConnection(
            'http://example.com', 'SECRET'
        );

        $this->service = new Service($connection, 'cloudServersOpenStack', 'DFW', 'publicURL');

        $this->resource = $this->service->flavor();
    }

    public function test___construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\Flavor', 
            $this->resource
        );
    }

    public function test__set1()
    {
        $this->resource->id = 'NEW';
        $this->assertEquals('NEW', $this->resource->id);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\AttributeError
     */
    public function test__set2()
    {
        $this->resource->foo = 'BAR';
        $this->assertEquals('BAR', $this->resource->foo);
    }

    public function testService()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service', 
            $this->resource->getService()
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
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDeleteFails()
    {
        $this->resource->delete();
    }

}