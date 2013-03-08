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

require_once('container.php');
require_once('stub_conn.php');
require_once('stub_service.php');
require_once('http.php');

class StubObjectStore extends OpenCloud\ObjectStore {
    public function Request($url, $method='GET', $headers=array(), $data=NULL) {
        return new OpenCloud\BlankResponse();
    }
}

class ContainerTest extends PHPUnit_Framework_TestCase
{
	private
		$service,
		$container;

	public function __construct() {
		$conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new StubObjectStore(
			$conn,
			'cloudFiles',
			'DFW',
			'publicURL'
		);
		$this->container = new OpenCloud\ObjectStore\Container(
		    $this->service,
		    'TEST');
	}

	/**
	 * Tests
	 */
	public function test_construct() {
	    $this->container = new OpenCloud\ObjectStore\Container(
	        $this->service, 'TEST');
		$this->assertEquals(
		    'TEST',
		    $this->container->name);
		$this->assertEquals(
		    'OpenCloud\Metadata',
		    get_class($this->container->metadata));
	}
	public function testUrl() {
		$this->assertEquals(
		    'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST',
		    $this->container->Url());
		$space_cont = new OpenCloud\ObjectStore\Container(
		    $this->service, 'Name With Spaces');
		$this->assertEquals(
	        'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/'.
	            'Name%20With%20Spaces',
		    $space_cont->Url());
	}
	public function testCreate() {
		$con = $this->container->Create(array('name'=>'SECOND'));
		$this->assertEquals(TRUE, $con);
		$this->assertEquals(
		    'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/SECOND',
		    $this->container->Url());
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\ContainerNameError
	 */
	public function testCreate0() {
		$con = $this->container->Create(array('name'=>'0'));
	}
	public function testUpdate() {
	    $this->assertEquals(
	        TRUE,
	        $this->container->Update());
	}
	public function testDelete() {
		$ret = $this->container->Delete();
		$this->assertEquals(TRUE, $ret);
	}
	public function testObjectList() {
		$olist = $this->container->ObjectList();
		$this->assertEquals(
		    'OpenCloud\Collection',
		    get_class($olist));
	}
	public function testDataObject() {
		$obj = $this->container->DataObject();
		$this->assertEquals(
		    'OpenCloud\ObjectStore\DataObject',
		    get_class($obj));
		$obj = $this->container->DataObject('FOO');
		$this->assertEquals('FOO', $obj->name);
	}
	public function testService() {
		$this->assertEquals($this->service, $this->container->Service());
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\ContainerNotFoundError
	 */
	public function testEnableCDN1() {
	    $this->container->EnableCDN(100);
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnTtlError
	 */
	public function testEnableCDN2() {
	    $this->container->EnableCDN('FOOBAR');
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnTtlError
	 */
	public function testPubishToCDN2() {
	    $this->container->PublishToCDN('FOOBAR');
	}
	/**
	 * @expectedException OpenCloud\ObjectStore\CdnHttpError
	 */
	public function testDisableCDN() {
	    $this->container->DisableCDN();
	}
	public function testCDNURL() {
	    $this->assertEquals(
	        'https://cdn1.clouddrive.com/v1/M-ALT-ID/TEST',
	        $this->container->CDNURL());
	}
	public function testCDNinfo() {
	    $this->assertEquals(
	        NULL,
	        $this->container->CDNinfo());
	}
	public function testCDNURI() {
	    $this->assertEquals(
	        NULL,
	        $this->container->CDNURI());
	}
	public function testSSLURI() {
	    $this->assertEquals(
	        NULL,
	        $this->container->SSLURI());
	}
	public function testStreamingURI() {
	    $this->assertEquals(
	        NULL,
	        $this->container->StreamingURI());
	}
	public function testCreateStaticSite() {
		$this->assertEquals(
			'OpenCloud\BlankResponse',
			get_class($this->container->CreateStaticSite('index.html')));
	}
	public function testStaticSiteErrorPage() {
		$this->assertEquals(
			'OpenCloud\BlankResponse',
			get_class($this->container->StaticSiteErrorPage('error.html')));
	}
	public function testPublicUrl() {
	    $this->assertEquals(
	        '',
	        $this->container->PublicUrl());
	}
}
