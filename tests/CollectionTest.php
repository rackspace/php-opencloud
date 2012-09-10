<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('collection.inc');

/**
 * Collection is an abstract class; this is a real one
 */
class MyCollection extends OpenCloud\Collection { }

class CollectionTest extends PHPUnit_Framework_TestCase
{
	private $my;

	/**
	 * create our private collection
	 */
	public function __construct() {
		$this->my = new MyCollection(
			'SERVICE',
			array('one', 'two', 'three', 'four'));
	}

	/**
	 * Tests
	 */
	public function test___construct() {
		$this->assertEquals('one', $this->my->First());
	}
	public function testService() {
		$this->assertEquals($this->my->Service(), 'SERVICE');
	}
	public function test_first_and_next() {
		$this->assertEquals($this->my->First(), 'one');
		$this->assertEquals($this->my->Next(), 'two');
		$this->assertEquals($this->my->Next(), 'three');
		$this->assertEquals($this->my->Next(), 'four');
		$this->assertEquals($this->my->Next(), FALSE);
	}
	public function testSize() {
	    $this->assertEquals(4, $this->my->Size());
	}
}
