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

namespace OpenCloud\Tests\ObjectStore;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Request\Response\Blank;
use OpenCloud\ObjectStore\Service;
use OpenCloud\ObjectStore\Resource\CDNContainer;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\Tests\StubConnection;

class StubObjectStore extends Service
{
    public function request($url, $method = 'GET', array $headers = array(), $data = null)
	{
        return new Blank;
    }
}

class ContainerTest extends PHPUnit_Framework_TestCase
{
	private $service;
	private $container;
    private $otherContainer;

	/**
	 * @expectedException OpenCloud\Common\Exceptions\ContainerNotFoundError
	 */
	public function __construct()
	{
		$conn = new StubConnection('http://example.com', 'SECRET');
		$this->service = new StubObjectStore(
			$conn,
			'cloudFiles',
			array('DFW'),
			'publicURL'
		);
        
        $this->otherContainer = new Container(new Service(
			new StubConnection('http://example.com', 'SECRET'),
			'cloudFiles',
			array('DFW'),
			'publicURL'
		), 'TEST');
	}

	public function getContainer()
	{
		if (null === $this->container) {
			$this->container = new Container($this->service, 'TEST');
		}
		return $this->container;
	}

	public function test_construct()
	{
	    $this->assertEquals('TEST', $this->getContainer()->name);
        
		$this->assertInstanceOf(
		    'OpenCloud\Common\Metadata', 
            $this->getContainer()->metadata
        );
	}

	public function testUrl()
	{
            $urls = $this->getContainer()->Url();
            foreach($urls as $url) {
                $this->assertEquals($url, 'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST');
            }

		$space_cont = new Container($this->service, 'Name With Spaces');

            $spaceUrls = $space_cont->Url();
            foreach($spaceUrls as $spaceUrl) {
                $this->assertEquals($spaceUrl, 'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/Name%20With%20Spaces');
            }
	}

	public function testCreate()
	{
		$con = $this->getContainer()->Create(array('name'=>'SECOND'));
		$this->assertEquals(TRUE, $con);
                $urls = $this->getContainer()->Url();
                foreach($urls as $url) {
                    $this->assertEquals($url, 'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/SECOND');
                }
	}

	public function testCreate0()
	{
		// '0' should be a valid container name
		$con = $this->getContainer()->Create(array('name'=>'0'));
		$this->assertTrue($con);
	}

	public function testUpdate()
	{
	    $this->assertEquals(
	        TRUE,
	        $this->getContainer()->Update());
	}

	public function testDelete()
	{
		$ret = $this->getContainer()->Delete();
		$this->assertEquals(TRUE, $ret);
	}

	public function testObjectList()
	{
		$olist = $this->getContainer()->ObjectList();
		$this->assertEquals(
		    'OpenCloud\Common\Collection',
		    get_class($olist));
	}

	public function testDataObject()
	{
		$obj = $this->getContainer()->DataObject();
		$this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $obj);
        
		$obj = $this->getContainer()->DataObject('FOO');
		$this->assertEquals('FOO', $obj->name);
	}

	public function testService()
	{
		$this->assertEquals($this->service, $this->getContainer()->getService());
	}

	public function testEnableCDN1()
	{
	    $this->getContainer()->enableCDN(100);
	}
    
    public function testCDNMetadata()
    {
        $container = $this->getContainer();
        $container->enableCDN(100);
        $container->CDN()->metadata = (object) array(
            'Enabled' => 'True',
            'X-Cdn-Streaming-Uri' => 'http://example.com'
        );
        
        $this->assertEquals('http://example.com', $container->CDNinfo('X-Cdn-Streaming-Uri'));
        $this->assertNull($container->CDNinfo('foo'));
        
        $this->assertTrue(null !== $container->CDNinfo());
    }

	/**
	 * @expectedException OpenCloud\Common\Exceptions\CdnTtlError
	 */
	public function testEnableCDN2()
	{
	    $this->getContainer()->enableCDN('FOOBAR');
	}

	/**
	 * @expectedException OpenCloud\Common\Exceptions\CdnTtlError
	 */
	public function testPubishToCDN2()
	{
	    $this->getContainer()->publishToCDN('FOOBAR');
	}

	public function testDisableCDN()
	{
	    $this->assertTrue($this->otherContainer->disableCDN());
	}

	public function testCDNURL()
	{
            $urls = $this->getContainer()->CDNURL();
            foreach($urls as $url) {
                $this->assertEquals($url, 'https://cdn1.clouddrive.com/v1/M-ALT-ID/TEST');
            }
	}

	public function testCDNinfo()
	{
	    $this->assertInstanceOf(
            'OpenCloud\Common\Metadata',
            $this->getContainer()->CDNinfo()
        );
	}

	public function testCDNURI()
	{
	    $this->assertEquals(
            'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            $this->getContainer()->CDNURI()
        );
	}

	public function testSSLURI()
	{
	    $this->assertEquals(
            'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
            $this->getContainer()->SSLURI()
        );
	}

	public function testStreamingURI()
	{
	    $this->assertEquals(
            'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
            $this->getContainer()->streamingURI()
        );
	}

	public function testIosStreamingURI()
	{
	    $this->assertEquals(
            'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.ios.cf0.rackcdn.com',
            $this->getContainer()->iosStreamingURI()
        );
	}

	public function testCreateStaticSite()
	{
		$this->assertInstanceOf(
			'OpenCloud\Common\Request\Response\Blank',
			$this->getContainer()->createStaticSite('index.html')
        );
	}

	public function testStaticSiteErrorPage()
	{
		$this->assertInstanceOf(
			'OpenCloud\Common\Request\Response\Blank',
			$this->getContainer()->StaticSiteErrorPage('error.html')
        );
	}

	public function testPublicUrl()
	{
	    $this->assertEquals(
            'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            $this->getContainer()->publicUrl()
        );
	}
    
    public function testCDNConstruct()
    {
        $cdn = new CDNContainer($this->service, (object) array(
            'metadata' => array(
                'foo' => 'bar'
            ),
            'name' => 'baz'
        ));
        $this->assertEquals('bar', $cdn->metadata->foo);
        $this->assertEquals('baz', $cdn->name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function testUrlFailsWithoutName()
    {
        $this->getContainer()->name = '';
        $this->getContainer()->url();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ContainerNameError
     */
    public function testNameFail1()
    {
        $name = '';
        $this->getContainer()->isValidName($name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ContainerNameError
     */
    public function testNameFail2()
    {
        $name = '/example/';
        $this->getContainer()->isValidName($name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ContainerNameError
     */
    public function testNameFail3()
    {
        $name = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
EOT;
        $this->getContainer()->isValidName($name);
    }
    
    public function testPseudoDirectories()
    {
        $files = $this->otherContainer->objectList(array(
           'delimiter' => '/',
           'prefix'    => 'files/'
        ));
        
        while ($file = $files->next()) {
            $this->assertTrue($file->isDirectory());
        }
    }
    
    public function testContainerNotFoundFailsGracefully()
    {
        $new = new Container(new Service(
			new StubConnection('http://example.com', 'SECRET'),
			'cloudFiles',
			array('DFW'),
			'publicURL'
		), 'foobar');
        $new->refresh('FOOBAR');
    }
    
}
