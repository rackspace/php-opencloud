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

class DataObjectTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $dataobject;
    private $service;
    private $container;
    private $nullFile; 
    private $nonCDNContainer;

    public function __construct()
    {
        $this->service = $this->getClient()->objectStore('cloudFiles', 'DFW');
        $this->container = $this->service->container('TEST');
        $this->dataobject = $this->container->dataObject('DATA-OBJECT');

        $this->nullFile = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'NUL' : '/dev/null';
        
        $this->nonCDNContainer = $this->service->container('NON-CDN');
        $this->nonCDNObject = $this->nonCDNContainer->dataObject('OBJECT');
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertEquals('DATA-OBJECT', $this->dataobject->name);
        $this->assertInstanceOf('OpenCloud\Common\Metadata', $this->dataobject->metadata);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST/DATA-OBJECT', 
            $this->dataobject->url()
        );
    }

    // tests objects with spaces
    public function testUrl2()
    {
        $testobject = $this->container->dataObject('A name with spaces');
        $this->assertEquals(
            'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST/A%20name%20with%20spaces', 
            $testobject->url()
        );
    }

    public function testCreate1()
    {
        $object = $this->container->dataObject();
        $object->create(array(
            'name' => 'DOOFUS', 
            'content_type' => 'text/plain'
        ));
        $this->assertEquals('DOOFUS', $object->name);
    }

    public function testCreateContentType()
    {
        $object = $this->container->dataObject();
        $object->create(array(
                'name' => 'MOOFUS', 
                'content_type' => 'application/x-arbitrary-mime-type'
            ), 
            __FILE__
        );
        $this->assertEquals('application/x-arbitrary-mime-type', $object->content_type);
    }

    public function testCreateWithHeaders()
    {
        $array = array(
            'name' => 'HOOFUS', 
            'extra_headers' => array('Access-Control-Allow-Origin' => 'http://example.com')
        );
        
        $object = $this->nonCDNObject;
        $object->create($array, $this->nullFile, 'tar.gz');
        
        $this->assertEquals('http://example.com', $object->extra_headers['Access-Control-Allow-Origin']);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ObjectError
     */
    public function testCreateFailsWithIncorrectExtractArchive()
    {
        $arr = array('name' => 'MOOFUS', 'content_type' => 'application/x-arbitrary-mime-type');
        $obj = $this->container->dataObject();
        $obj->create($arr, __FILE__, 'wrong.tar.gz');
    }

    public function testUpdate()
    {
        $arr = array('name' => 'XOOFUS', 'content_type' => 'text/plain');
        $obj = $this->container->dataObject();
        $this->assertInstanceOf(
            'OpenCloud\Common\Http\Message\Response', 
            $obj->update($arr)
        );
        $this->assertEquals('XOOFUS', $obj->name);
    }

    public function testDelete()
    {
        $this->dataobject->delete(array('content_type' => 'text/plain'));
        $this->assertEquals('DATA-OBJECT', $this->dataobject->name);
    }

    public function testUpdateMetadata()
    {
        $this->dataobject->updateMetadata(array('content_type' => 'text/html'));
        $this->assertEquals('text/html', $this->dataobject->content_type);
    }

    public function testCopy()
    {
        $target = $this->container->DataObject();
        $target->name = 'DESTINATION';
        $this->assertInstanceOf(
            'OpenCloud\Common\Http\Message\Response', 
            $this->dataobject->Copy($target)
        );
    }

    public function testContainer()
    {
        $this->assertInstanceOf(
            'OpenCloud\ObjectStore\Resource\Container', 
            $this->dataobject->container()
        );
    }

    public function testTempUrl1()
    {
        $this->assertEquals(
            0, 
            strpos(
                'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST/DATA-OBJECT?temp_url_sig', 
                $this->dataobject->TempUrl('secret', 60, 'GET')
            )
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\TempUrlMethodError
     */
    public function testTempUrl2()
    {
        // POST is invalid
        $this->dataobject->tempUrl('secret', 60, 'POST');
    }

    public function testSetData()
    {
        $this->dataobject->setData(12345);
        $this->dataobject->setData('hello');
    }

    public function testSaveToString()
    {
        $this->assertRegExp('/200 OK/', $this->dataobject->saveToString());
    }

    public function testSaveToFilename()
    {
        $this->dataobject->saveToFilename($this->nullFile);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\IOError
     */
    public function testSaveToFilenameFailsWithoutPath()
    {
        $this->dataobject->saveToFilename('/foo/bar');
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ObjectError
     */
    public function testSaveToStreamFailsWithoutResource()
    {
        $this->dataobject->saveToStream('foo');
    }
    
    public function testSaveToStream()
    {
        $fp = fopen('php://temp', 'r+');
        $this->dataobject->saveToStream($fp);
        fclose($fp);
    }
    
    public function testgetETag()
    {
        $this->assertNull($this->dataobject->getETag());
    }

    public function testFetch()
    {
        $obj = $this->container->dataObject('FOO');
        $this->assertEquals('FOO', $obj->name);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function testFetchFailsWithoutName()
    {
        $this->container->dataObject()->refresh();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnNotAvailableError
     */
    public function testCDNURL()
    {
        $this->assertEquals('foo', $this->dataobject->CDNURL());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnNotAvailableError
     */
    public function testPurgeCDN()
    {
        $this->dataobject->PurgeCDN('glen@glenc.co');
    }
    
    public function testPurgeFailsWithNonCDNContainer()
    {
        $this->nonCDNObject->purgeCDN('foo@bar.com');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnNotAvailableError
     */
    public function testPublicURL()
    {
        $this->assertEquals('foo', $this->dataobject->PublicURL());
        $this->assertEquals('foozzz', $this->dataobject->PublicURL('ios-streaming'));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\IOError
     */
    public function testCreateFailsWithoutCorrectPath()
    {
        $this->dataobject->create(array('name' => 'FOOBAR'), __DIR__ . '/foo');
    }
    
    public function testMIMECheck()
    {
        $this->dataobject->content_type = null;
        $this->dataobject->create(array('name' => 'FOOBAR', 'foo' => 'bar'), __DIR__ . '/Files/example.json');
        
        $this->assertObjectNotHasAttribute('foo', $this->dataobject);
    }
    
    public function testUrls()
    {
        $files = $this->container->objectList();
        $file = $files->first();
        
        $this->assertNotNull($file->publicURL());
        $this->assertNotNull($file->publicURL('STREAMING'));
        $this->assertNotNull($file->publicURL('SSL'));
        $this->assertNotNull($file->publicURL('IOS-STREAMING'));
        
        $files2 = $this->nonCDNContainer->objectList();
        $file2 = $files2->first();
        $this->assertNull($file2->publicURL());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function testUrlFailsWithoutName()
    {
        $this->nonCDNObject->name = null;
        $this->nonCDNObject->url();
    }
}
