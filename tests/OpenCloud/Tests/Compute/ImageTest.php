<?php
/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

use OpenCloud\Tests\StubConnection;
use PHPUnit_Framework_TestCase;
use OpenCloud\Compute\Image;
use OpenCloud\Compute\Service;

class ImageStub extends Image
{

}

class ImageTest extends PHPUnit_Framework_TestCase
{

    private $compute;

    public function __construct()
    {
        $connection = new StubConnection('http://example.com', 'SECRET');
        $this->compute = new Service(
            $connection, 'cloudServersOpenStack', 'DFW', 'publicURL'
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\InstanceNotFound
     */
    public function test___construct()
    {
        $image = new Image($this->compute, 'XXXXXX');
    }

    public function test_good_image()
    {
        $image = new Image($this->compute);
        $this->assertEquals(null, $image->status);
        $this->assertEquals('OpenCloud\Common\Metadata', get_class($image->metadata));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\JsonError
     */
    public function test_bad_json()
    {
        $image = new Image($this->compute, 'BADJSON');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\EmptyResponseError
     */
    public function test_empty_json()
    {
        $image = new Image($this->compute, 'EMPTY');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreate()
    {
        $image = $this->compute->image();
        $image->create();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $image = $this->compute->image();
        $image->update();
    }

    public function testJsonName()
    {
        $x = new ImageStub($this->compute);
        $this->assertEquals('image', $x->jsonName());
    }

}
