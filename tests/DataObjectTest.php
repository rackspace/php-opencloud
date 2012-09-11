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

require_once('dataobject.inc');
require_once('stub_conn.inc');
require_once('stub_service.inc');

class DataObjectTest extends PHPUnit_Framework_TestCase
{
	private
	    $dataobject,
		$service,
		$container;

	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new StubService(
			$conn,
			'object-store',
			'cloudFiles',
			'DFW',
			'publicURL'
		);
		$this->container =
			new OpenCloud\ObjectStore\Container(
			    $this->service, 'TEST');
		$this->dataobject =
			new OpenCloud\ObjectStore\DataObject(
			    $this->container, 'DATA-OBJECT');
	}

	/**
	 * Tests
	 */
	public function test__construct() {
		$this->assertEquals(
		    'DATA-OBJECT',
		    $this->dataobject->name);
		$this->assertEquals(
		    'OpenCloud\Metadata',
		    get_class($this->dataobject->metadata));
	}
	public function testUrl() {
		$this->assertEquals(
		'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST/DATA-OBJECT',
		$this->dataobject->Url());
	}
	public function testCreate() {
		$arr = array('name'=>'DOOFUS', 'type'=>'text/plain');
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		$this->assertEquals(
		    'OpenCloud\BlankResponse',
		    get_class($obj->Create($arr)));
		$this->assertEquals('DOOFUS', $obj->name);
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		$this->assertEquals(
		    'OpenCloud\BlankResponse',
		    get_class($obj->Create(array('name'=>'FOOBAR'), '/dev/null')));
	}
	public function testUpdate() {
		$arr = array('name'=>'XOOFUS', 'type'=>'text/plain');
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		$this->assertEquals(
		    'OpenCloud\BlankResponse',
		    get_class($obj->Update($arr)));
		$this->assertEquals('XOOFUS', $obj->name);
	}
	public function testDelete() {
	    $this->dataobject->Delete(array('type'=>'text/plain'));
	    $this->assertEquals('DATA-OBJECT', $this->dataobject->name);
	}
	public function testCopy() {
	    $target = $this->container->Object();
	    $target->name = 'DESTINATION';
	    $this->assertEquals(
	        'OpenCloud\BlankResponse',
	        get_class($this->dataobject->Copy($target)));
	}
	public function testContainer() {
	    $this->assertEquals(
	        'OpenCloud\ObjectStore\Container',
	        get_class($this->dataobject->Container()));
	}
	public function testSetData() {
	    $this->dataobject->SetData(12345);
	    $this->dataobject->SetData('hello');
	}
	public function testSaveToString() {
	    $this->assertRegExp(
	        '/200 OK/',
	        $this->dataobject->SaveToString());
	}
	public function testSaveToFilename() {
	    $this->dataobject->SaveToFilename('/dev/null');
	}
	public function testgetETag() {
	    $this->assertEquals(
	        NULL,
	        $this->dataobject->getETag());
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\UnknownParameterError
	 */
	public function testSetParams() {
	    $this->dataobject->Delete(array('FOO'=>'BAR'));
	    $this->assertEquals(
	        'BAR',
	        $this->dataobject->FOO);
	}
	public function testFetch() {
	    $obj = new OpenCloud\ObjectStore\DataObject($this->container, 'FOO');
	    $this->assertEquals('FOO', $obj->name);
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnNotAvailableError
	 */
	public function testCDNURL() {
	    $this->assertEquals(
	        'foo',
	        $this->dataobject->CDNURL());
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnNotAvailableError
	 */
	public function testPurgeCDN() {
	    $this->dataobject->PurgeCDN('glen@glenc.co');
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnNotAvailableError
	 */
	public function testPublicURL() {
	    $this->assertEquals(
	        'foo',
	        $this->dataobject->PublicURL());
	}
}
