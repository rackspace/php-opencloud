<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('objectstore.inc');
require_once('stub_conn.inc');

/**
 * Stub wrapper class so that we can override the request() method
 */
class MyObjectStore extends \OpenCloud\ObjectStore {
	public function Request() {
		return new OpenCloud\BlankResponse();
	}
}

class ObjectStoreTest extends PHPUnit_Framework_TestCase
{
	private
		$ostore;
	public function __construct() {
		$conn = new StubConnection('http://example.com','SECRET');
		$this->ostore = new MyObjectStore(
			$conn,
			'cloudFiles',
			'DFW',
			'publicURL'
		);
	}

	/**
	 * Tests
	 */
	public function test__construct() {
		$this->assertEquals(TRUE, is_object($this->ostore));
		$this->assertEquals(
		    'MyObjectStore',
		    get_class($this->ostore));
	}
	public function testUrl() {
		$this->assertEquals(
			'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID',
			$this->ostore->Url());
	}
	public function testContainer() {
		$obj = $this->ostore->Container();
		$this->assertEquals('OpenCloud\ObjectStore\Container', get_class($obj));
	}
	public function testContainerList() {
		$clist = $this->ostore->ContainerList();
		$this->assertEquals(
		    'OpenCloud\ObjectStore\ContainerList',
		    get_class($clist));
	}
	public function testCDN() {
	    $this->assertEquals(
	        'OpenCloud\ObjectStoreCDN',
	        get_class($this->ostore->CDN()));
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnError
	 */
	public function testCDNCDN() {
	    $this->assertEquals(
	        FALSE,
	        get_class($this->ostore->CDN()->CDN()));
	}
}
