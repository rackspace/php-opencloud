<?php
/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

require_once('dataobject.php');
require_once('stub_conn.php');
require_once('stub_service.php');

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
	// tests objects with spaces
	public function testUrl2() {
		$testobject = new OpenCloud\ObjectStore\DataObject(
			$this->container, 'A name with spaces');
		$this->assertEquals(
		'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST/'.
			'A%20name%20with%20spaces',
		$testobject->Url());
	}
	public function testCreate1() {
		$arr = array('name'=>'DOOFUS', 'content_type'=>'text/plain');
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
	/**
	 * @expectedException OpenCloud\ObjectStore\UnknownParameterError
	 */
	public function testCreate2() {
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		// "type" is deprecated; this causes the exception
		$obj->Create(array('name'=>'X','type'=>'error'));
	}
	public function testCreateContentType() {
		$arr = array('name'=>'MOOFUS', 'content_type'=>'application/x-arbitrary-mime-type');
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		$obj->Create($arr, __FILE__);
		$this->assertEquals('application/x-arbitrary-mime-type', $obj->content_type);
	}
	public function testCreateWithHeaders() {
		$arr = array('name'=>'HOOFUS',
		             'extra_headers'=>array('Access-Control-Allow-Origin'=>'http://example.com'));
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		$obj->Create($arr, __FILE__);
		$this->assertEquals('http://example.com', $obj->extra_headers['Access-Control-Allow-Origin']);

		//$obj2 = new OpenCloud\ObjectStore\DataObject($this->container, 'HOOFUS');
		//$this->assertArrayHasKey('Access-Control-Allow-Origin', $obj2->extra_headers);
		//$this->assertEquals('http://example.com', $obj2->extra_headers['Access-Control-Allow-Origin']);
	}
	public function testUpdate() {
		$arr = array('name'=>'XOOFUS', 'content_type'=>'text/plain');
		$obj = new OpenCloud\ObjectStore\DataObject($this->container);
		$this->assertEquals(
		    'OpenCloud\BlankResponse',
		    get_class($obj->Update($arr)));
		$this->assertEquals('XOOFUS', $obj->name);
	}
	public function testDelete() {
	    $this->dataobject->Delete(array('content_type'=>'text/plain'));
	    $this->assertEquals('DATA-OBJECT', $this->dataobject->name);
	}
	public function testCopy() {
	    $target = $this->container->DataObject();
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
	public function testTempUrl1() {
		$this->assertEquals(
			0,
			strpos(
				'https://storage101.dfw1.clouddrive.com/v1/'.
				'M-ALT-ID/TEST/DATA-OBJECT?temp_url_sig',
				$this->dataobject->TempUrl('secret', 60, 'GET')));
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\TempUrlMethodError
	 */
	public function testTempUrl2() {
		// POST is invalid
		$this->dataobject->TempUrl('secret', 60, 'POST');
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
