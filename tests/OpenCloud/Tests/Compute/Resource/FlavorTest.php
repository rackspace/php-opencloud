<?php

/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Tests\Compute\ComputeTestCase;

class FlavorTest extends ComputeTestCase
{
    const FLAVOR_ID = '';

    public function setupObjects()
    {
        parent::setupObjects();

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