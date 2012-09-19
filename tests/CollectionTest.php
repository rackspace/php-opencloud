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
	public function foobar($item) {
		$obj = new stdClass();
	    if (is_array($item) || is_object($item)) {
	        foreach($item as $k => $v)
	            $obj->$k = $v;
	    }
	    else
		    $obj->id = $item;
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
	    $x = new Gertrude;
		$this->my = new OpenCloud\Collection(
			$x,
			'foobar',
			//array('one', 'two', 'three', 'four'));
			array(
			    (object)array('id'=>'one'),
			    (object)array('id'=>'two'),
			    (object)array('id'=>'three'),
			    (object)array('id'=>'four'),
			));
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
	public function testSort() {
	    $this->my->Sort();
	    $this->assertEquals(
	        'four',
	        $this->my->First()->id);
	}
}
