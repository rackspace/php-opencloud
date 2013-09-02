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
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\ObjectStore\Resource\DataObject;
use OpenCloud\Tests\StubConnection;
use OpenCloud\Tests\StubService;

class DataObjectTest extends PHPUnit_Framework_TestCase
{

    private $dataobject;
    private $service;
    private $container;
    private $nullFile;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new StubService(
            $conn, 'object-store', 'cloudFiles', 'DFW', 'publicURL'
        );
        $this->container = new Container($this->service, 'TEST');
        $this->dataobject = new DataObject($this->container, 'DATA-OBJECT');

        $this->nullFile = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'NUL' : '/dev/null';
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
            $this->dataobject->Url()
        );
    }

    // tests objects with spaces
    public function testUrl2()
    {
        $testobject = new DataObject($this->container, 'A name with spaces');
        $this->assertEquals(
            'https://storage101.dfw1.clouddrive.com/v1/M-ALT-ID/TEST/A%20name%20with%20spaces', 
            $testobject->Url()
        );
    }

    public function testCreate1()
    {
        $arr = array('name' => 'DOOFUS', 'content_type' => 'text/plain');
        
        $obj = new DataObject($this->container);
        $this->assertInstanceOf('OpenCloud\Common\Request\Response\Blank', $obj->Create($arr));
        $this->assertEquals('DOOFUS', $obj->name);
        
        $obj = new DataObject($this->container);
        $this->assertInstanceOf(
            'OpenCloud\Common\Request\Response\Blank', 
            $obj->create(array('name' => 'FOOBAR'), $this->nullFile)
        );
    }

    public function testCreateContentType()
    {
        $arr = array('name' => 'MOOFUS', 'content_type' => 'application/x-arbitrary-mime-type');
        $obj = new DataObject($this->container);
        $obj->create($arr, __FILE__);
        $this->assertEquals('application/x-arbitrary-mime-type', $obj->content_type);
    }

    public function testCreateWithHeaders()
    {
        $arr = array('name' => 'HOOFUS', 'extra_headers' => array('Access-Control-Allow-Origin' => 'http://example.com'));
        $obj = new DataObject($this->container);
        $obj->create($arr, $this->nullFile);
        $this->assertEquals('http://example.com', $obj->extra_headers['Access-Control-Allow-Origin']);
    }

    public function testUpdate()
    {
        $arr = array('name' => 'XOOFUS', 'content_type' => 'text/plain');
        $obj = new DataObject($this->container);
        $this->assertInstanceOf(
            'OpenCloud\Common\Request\Response\Blank', 
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
            'OpenCloud\Common\Request\Response\Blank', 
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

    public function testgetETag()
    {
        $this->assertNull($this->dataobject->getETag());
    }

    public function testFetch()
    {
        $obj = new DataObject($this->container, 'FOO');
        $this->assertEquals('FOO', $obj->name);
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

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnNotAvailableError
     */
    public function testPublicURL()
    {
        $this->assertEquals('foo', $this->dataobject->PublicURL());
        $this->assertEquals('foozzz', $this->dataobject->PublicURL('ios-streaming'));
    }

}
