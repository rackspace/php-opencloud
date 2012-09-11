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

require_once('stub_conn.inc');
require_once('metadata.inc');
require_once('compute.inc');

class MetadataTest extends PHPUnit_Framework_TestCase
{
	private
		$metadata;
	public function __construct() {
		$this->metadata = new OpenCloud\Metadata();
	}
	/**
	 * Tests
	 */
    public function test__set() {
        $this->metadata->foo = 'bar';
        $this->assertEquals('bar', $this->metadata->foo);
    }
    public function testKeylist() {
        $this->metadata->foo = 'bar';
        $this->assertEquals(TRUE, in_array('foo', $this->metadata->Keylist()));
    }
    public function testSetArray() {
        $this->metadata->SetArray(array('opt'=>'uno','foobar'=>'baz'));
        $this->assertEquals('uno', $this->metadata->opt);
    }
}
