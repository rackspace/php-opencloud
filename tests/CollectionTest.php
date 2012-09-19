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

require_once('collection.inc');

class Gertrude {
	public function foobar($id) {
		$obj = new stdClass();
		$obj->id = $id;
		return $obj;
	}
}

class CollectionTest extends PHPUnit_Framework_TestCase
{
	private $my;

	/**
	 * create our private collection
	 */
	public function __construct() {
		$this->my = new OpenCloud\Collection(
			new Gertrude,
			'foobar',
			array('one', 'two', 'three', 'four'));
	}

	/**
	 * Tests
	 */
	public function test___construct() {
		$this->assertEquals('one', $this->my->First()->id);
	}
	public function testService() {
		$this->assertEquals('Gertrude', get_class($this->my->Service()));
	}
	public function test_first_and_next() {
		$this->assertEquals($this->my->First()->id, 'one');
		$this->assertEquals($this->my->Next()->id, 'two');
		$this->assertEquals($this->my->Next()->id, 'three');
		$this->assertEquals($this->my->Next()->id, 'four');
		$this->assertEquals($this->my->Next(), FALSE);
	}
	public function testReset() {
		$first = $this->my->First();
		$this->my->Reset();
		$this->assertEquals(
			$first,
			$this->my->Next());
	}
	public function testSize() {
	    $this->assertEquals(4, $this->my->Size());
	}
}
