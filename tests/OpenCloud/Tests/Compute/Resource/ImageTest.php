<?php
/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Compute\Resource\Image;
use OpenCloud\Tests\Compute\ComputeTestCase;

class ImageTest extends ComputeTestCase
{

    public function test_good_image()
    {
        $image = new Image($this->service);
        $this->assertEquals(null, $image->status);
        $this->assertEquals('OpenCloud\Common\Metadata', get_class($image->getMetadata()));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreate()
    {
        $image = $this->service->image();
        $image->create();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $image = $this->service->image();
        $image->update();
    }

}
