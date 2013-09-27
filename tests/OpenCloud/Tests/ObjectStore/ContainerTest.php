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

use OpenCloud\ObjectStore\Resource\CDNContainer;

class ContainerTest extends \OpenCloud\Tests\OpenCloudTestCase
{
	private $service;
	private $container;
    private $otherContainer;

	/**
	 * @expectedException OpenCloud\Common\Exceptions\ContainerNotFoundError
	 */
	public function __construct()
	{
		$this->service = $this->getClient()->objectStore('cloudFiles', 'DFW', 'publicURL');
        $this->container = $this->service->container('TEST');
        $this->otherContainer = $this->service->container('TEST');
	}

	public function test_construct()
	{
	    $this->assertEquals('TEST', $this->container->name);
        
		$this->assertInstanceOf(
		    'OpenCloud\Common\Metadata', 
            $this->container->metadata
        );
	}

	public function testUrl()
	{
		$this->assertEquals(
		    'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST',
		    $this->container->Url()
		);

		$space_cont = $this->service->container('Name With Spaces');

		$this->assertEquals(
	        'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/Name%20With%20Spaces',
		    $space_cont->Url()
		);
	}

	public function testCreate()
	{
		$con = $this->container->create(array('name'=>'SECOND'));
		$this->assertTrue($con);
		$this->assertEquals(
		    'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/SECOND',
		    $this->container->Url()
        );
	}

	public function testCreate0()
	{
		// '0' should be a valid container name
		$con = $this->container->create(array('name'=>'0'));
		$this->assertTrue($con);
	}

	public function testUpdate()
	{
	    $this->assertTrue($this->container->update());
	}

	public function testDelete()
	{
		$this->assertTrue($this->container->delete());
	}

	public function testObjectList()
	{
		$this->assertInstanceOf(
		    'OpenCloud\Common\Collection',
		    $this->container->objectList()
        );
	}

	public function testDataObject()
	{
		$obj = $this->container->dataObject();
		$this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $obj);
        
		$obj = $this->container->dataObject('FOO');
		$this->assertEquals('FOO', $obj->name);
	}

	public function testService()
	{
		$this->assertEquals($this->service, $this->container->getService());
	}

	public function testEnableCDN1()
	{
	    $this->container->enableCDN(100);
	}
    
    public function testCDNMetadata()
    {
        $container = $this->container;
        $container->enableCDN(100);
        $container->CDN()->metadata = (object) array(
            'Enabled' => 'True',
            'X-Cdn-Streaming-Uri' => 'http://example.com'
        );
        
        $this->assertEquals('http://example.com', $container->CDNinfo('X-Cdn-Streaming-Uri'));
        $this->assertNull($container->CDNinfo('foo'));
        
        $this->assertNotNull($container->CDNinfo());
    }

	/**
	 * @expectedException OpenCloud\Common\Exceptions\CdnTtlError
	 */
	public function testEnableCDN2()
	{
	    $this->container->enableCDN('FOOBAR');
	}

	/**
	 * @expectedException OpenCloud\Common\Exceptions\CdnTtlError
	 */
	public function testPubishToCDN2()
	{
	    $this->container->publishToCDN('FOOBAR');
	}

	public function testDisableCDN()
	{
	    $this->assertTrue($this->otherContainer->disableCDN());
	}

	public function testCDNURL()
	{
	    $this->assertEquals(
	        'https://cdn1.clouddrive.com/v1/M-ALT-ID/TEST',
	        $this->container->CDNURL()
        );
	}

	public function testCDNinfo()
	{
        $this->assertInstanceOf(
            'OpenCloud\Common\Metadata',
            $this->container->CDNinfo()
        );
	}

	public function testCDNURI()
	{
	    $this->assertEquals(
            'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            $this->container->CDNURI()
        );
	}

	public function testSSLURI()
	{
	    $this->assertEquals(
            'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
            $this->container->SSLURI()
        );
	}

	public function testStreamingURI()
	{
	    $this->assertEquals(
            'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
            $this->container->streamingURI()
        );
	}

	public function testIosStreamingURI()
	{
	    $this->assertEquals(
            'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.ios.cf0.rackcdn.com',
            $this->container->iosStreamingURI()
        );
	}

	public function testCreateStaticSite()
	{
		$this->assertInstanceOf(
			'OpenCloud\Common\Http\Message\Response',
			$this->container->createStaticSite('index.html')
        );
	}

	public function testStaticSiteErrorPage()
	{
		$this->assertInstanceOf(
			'OpenCloud\Common\Http\Message\Response',
			$this->container->StaticSiteErrorPage('error.html')
        );
	}

	public function testPublicUrl()
	{
	    $this->assertEquals(
            'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            $this->container->publicUrl()
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
        $this->container->name = '';
        $this->container->url();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ContainerNameError
     */
    public function testNameFail1()
    {
        $name = '';
        $this->container->isValidName($name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ContainerNameError
     */
    public function testNameFail2()
    {
        $name = '/example/';
        $this->container->isValidName($name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ContainerNameError
     */
    public function testNameFail3()
    {
        $name = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
EOT;
        $this->container->isValidName($name);
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
        $new = $this->service->container('foobar');
        $new->refresh('FOOBAR');
    }
    
}
