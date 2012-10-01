<?php
/**
 * Unit Tests
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

require_once('image.inc');
require_once('compute.inc');
require_once('stub_conn.inc');

class ImageTest extends PHPUnit_Framework_TestCase
{
	private
		$compute;
	public function __construct() {
		$conn = new StubConnection('http://example.com','SECRET');
		$this->compute = new OpenCloud\Compute($conn,
		    'cloudServersOpenStack','DFW','publicURL');
	}
	/**
	 * Tests
	 */

	/**
	 * @expectedException OpenCloud\Compute\InstanceNotFound
	 */
	public function test___construct() {
		$image = new OpenCloud\Compute\Image($this->compute, 'XXXXXX');
		$this->assertEquals('OpenCloud\Metadata', get_class($image->metadata));
    }
    public function test_good_image() {
		$image = new OpenCloud\Compute\Image($this->compute);
		$this->assertEquals(NULL, $image->status);
		$this->assertEquals('OpenCloud\Metadata', get_class($image->metadata));
	}
	/**
	 * @expectedException OpenCloud\JsonError
	 */
    public function test_bad_json() {
		$image = new OpenCloud\Compute\Image($this->compute, 'BADJSON');
    }
    /**
     * @expectedException OpenCloud\Compute\EmptyResponseError
     */
    public function test_empty_json() {
		$image = new OpenCloud\Compute\Image($this->compute, 'EMPTY');
    }
    public function test__set1() {
		$image = $this->compute->Image();
		$image->id = 'NEW';
		$this->assertEquals('NEW', $image->id);
	}
    /**
     * @expectedException OpenCloud\AttributeError
     */
	public function test__set2() {
		$image = $this->compute->Image();
		$image->foo = 'BAR';
		$this->assertEquals('BAR', $image->foo);
    }
    public function testService() {
		$image = $this->compute->Image();
		$this->assertEquals('OpenCloud\Compute', get_class($image->Service()));
    }
    /**
     * @expectedException OpenCloud\CreateError
     */
    public function testCreate() {
    	$image = $this->compute->Image();
    	$image->Create();
    }
    /**
     * @expectedException OpenCloud\UpdateError
     */
    public function testUpdate() {
    	$image = $this->compute->Image();
    	$image->Update();
    }
}
