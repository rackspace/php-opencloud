<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

define('RAXSDK_FATAL_HALT', False);	// don't halt on Fatal errors
require_once('base.inc');

/**
 * Can't test Base directly, since it is an abstract class, so we instantiate it
 */
class MyBase extends OpenCloud\Base {
    public
        $foo; // to test SetProperty
    public function GetHttpRequestObject($url, $method='GET') {
        return parent::GetHttpRequestObject($url, $method);
    }
}

class BaseTest extends PHPUnit_Framework_TestCase
{
	private $my;

	/**
	 * create our redirected Base class
	 */
	public function __construct() {
		$this->my = new MyBase;
	}

	/**
	 * Tests
	 */
	public function test_gettext() {
		$this->assertEquals(_('Hello'), 'Hello');
	}
	public function test_noslash() {
		$this->assertEquals(noslash('String/'), 'String');
		$this->assertEquals(noslash('String'), 'String');
	}
	public function testDebug() {
        setDebug(TRUE);
	    $this->expectOutputRegex('/ELLO/');
	    $this->my->debug("HELLO, WORLD!");
	    setDebug(FALSE);
	}
	/**
	 * @expectedException OpenCloud\URLError
	 */
	public function testUrl() {
		$this->my->Url();
	}
	public function testGetHttpRequestObject() {
	    $request = $this->my->GetHttpRequestObject('file:/dev/null');
	    $this->assertEquals(
	        'OpenCloud\CurlRequest',
	        get_class($request));
	}
	/**
	 * @expectedException OpenCloud\AttributeError
	 */
	public function test__set() {
		$this->my->foobar = 'baz'; // should cause error
		$this->expectOutputRegEx('/Unrecognized attribute/');
	}
	public function testMakeQueryString() {
	    $this->assertEquals(
	        'A=1',
	        $this->my->MakeQueryString(array('A'=>1)));
	    $this->assertEquals(
	        'A=1&B=2',
	        $this->my->MakeQueryString(array('A'=>1,'B'=>2)));
	}
	/**
	 * @expectedException OpenCloud\JsonError
	 */
	public function testCheckJsonError() {
	    $json = '{"one":"two"}';
	    $obj = json_decode($json);
	    $this->assertEquals(FALSE, $this->my->CheckJsonError());
	    $json = '{"one":"two"';
	    $obj = json_decode($json);
	    $this->assertEquals(TRUE, $this->my->CheckJsonError());
	}
	/**
	 * @expectedException OpenCloud\AttributeError
	 */
	public function testSetProperty() {
	    $this->my->foo = 'bar';
	    $this->assertEquals('bar', $this->my->foo);
	    $this->my->SetProperty('one', 'two');
	    $this->assertEquals('two', $this->my->one);
	}
}
