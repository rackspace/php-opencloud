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

namespace OpenCloud\Tests;

use PHPUnit_Framework_TestCase;
use OpenCloud\OpenStack;
use OpenCloud\Tests\StubConnection;

/**
 * stub classes for testing the request() method (which is overridden in the
 * StubConnection class used for testing everything else).
 */
class TestingConnection extends OpenStack
{

    public function getHttpRequestObject($url, $method = 'GET', array $options = array())
    {
        return new StubRequest($url, $method);
    }

}

class OpenStackTest extends PHPUnit_Framework_TestCase
{
    
    const TEST_DOMAIN = 'http://local.test';
    
    private $my;
    private $nullFile;
    private $testConn;
    
    public function __construct()
    {
        $this->nullFile = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'NUL' : '/dev/null';
        
        $this->my = new StubConnection(self::TEST_DOMAIN, array(
            'username' => 'Foo', 
            'password' => 'Bar'
        ));
        
        $this->testConn = new TestingConnection('http://example.com', array(
            'username' => 'Foo', 
            'password' => 'Bar'
        ));
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Tests\StubConnection', $this->my);
        $this->assertEquals(self::TEST_DOMAIN, $this->my->getUrl());
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function test__options()
    {
        $test = new StubConnection(
            self::TEST_DOMAIN, 
            array(
                'username' => 'Foo', 
                'password' => 'Bar'
            ), 
            'bad option'
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CredentialError
     */
    public function testBadInvalidCredentialFormat()
    {
        $test = new OpenStack(self::TEST_DOMAIN, array('foo' => 'bar'));
        $test->credentials();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\AuthenticationError
     */
    public function testBadCredentials()
    {
        $test = new StubConnection(self::TEST_DOMAIN, array('username' => 'bar', 'password' => 'badPassword'));
        $test->authenticate();
    }
    
    public function testUrl()
    {
        $this->assertEquals(self::TEST_DOMAIN . '/tokens', $this->my->Url());
    }

    public function testSecret()
    {
        $arr = $this->my->Secret();
        $this->assertEquals($arr['username'], 'Foo');
    }

    public function testToken()
    {
        $this->assertEquals('TOKEN-ID', $this->my->Token());
    }

    public function testTenant()
    {
        $this->assertEquals('TENANT-ID', $this->my->Tenant());
    }

    public function testExpiration()
    {
        $this->assertEquals('978374510', $this->my->Expiration());
    }

    public function testServiceCatalog()
    {
        $cat = $this->my->serviceCatalog();
        $this->assertEquals('DFW', $cat[0]->endpoints[0]->region);
    }

    public function testCredentials()
    {
        $this->assertRegExp(
            '/"passwordCredentials"/', 
            $this->my->Credentials()
        );
        
        $test = new StubConnection(self::TEST_DOMAIN, array(
            'username' => 'Foo', 
            'password' => 'Bar', 
            'tenantName' => 'Phil'
        ));
        
        $this->assertRegExp(
            '/"tenantName":"Phil"/', 
            $test->Credentials()
        );
    }

    public function testAuthenticate()
    {
        $this->my->Authenticate();
        $this->assertEquals('TOKEN-ID', $this->my->Token());
    }

    /**
     * Since the request() method is overridden in the StubConnection class,
     * we need to get this one to use the real code.
     */
    public function testRequest()
    {
        $response = $this->testConn->request('GOOD', 'GET', array(), 'foo=bar');
        $this->assertEquals(200, $response->HttpStatus());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpUnauthorizedError
     */
    public function test_request_2()
    {
        $response = $this->testConn->request('401');
        $this->assertEquals(200, $response->HttpStatus());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpForbiddenError
     */
    public function test_request_3()
    {
        $response = $this->testConn->request('403');
        $this->assertEquals(200, $response->HttpStatus());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpOverLimitError
     */
    public function test_request_4()
    {
        $response = $this->testConn->Request('413');
        $this->assertEquals(200, $response->HttpStatus());
    }

    public function test_request_5()
    {
        $this->testConn->request(
            self::TEST_DOMAIN . '/test_payload',
            'POST',
            array('Content-Length' => 10),
            fopen($this->nullFile, 'r')
        );
        
        $this->testConn->request(
            self::TEST_DOMAIN . '/test_payload',
            'HEAD',
            array(),
            fopen($this->nullFile, 'r')
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpError
     */
    public function testPostingDataFailsWithoutContentLength()
    {
        $this->testConn->request(
            self::TEST_DOMAIN . '/test_payload',
            'POST',
            array(),
            fopen($this->nullFile, 'r')
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpError
     */
    public function testRequestFailsWithoutCorrectDataType()
    {
        $this->testConn->request(
            self::TEST_DOMAIN . '/test_payload',
            'POST',
            array(),
            array()
        );
    }
    
    public function testAppendUserAgent()
    {
        $this->my->setUserAgent('BAZ');
        $this->assertEquals('BAZ', $this->my->getUserAgent());
        
        $this->my->appendUserAgent('FOOBAR');
        $this->assertEquals('BAZ;FOOBAR', $this->my->getUserAgent());
    }

    public function testSetDefaults()
    {
        $this->my->setDefaults('Compute', 'cloudServersOpenStack', array('DFW'), 'publicURL');
        $comp = $this->my->Compute();
        $urls = $comp->Url();
        foreach($urls as $url) {
            $this->assertRegExp('/dfw.servers/', $url);
        }
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UnrecognizedServiceError
     */
    public function testSettingDefaultsFailsWithoutSetService()
    {
        $this->my->setDefaults('fooBar', 'baz');
    }

    public function testSetTimeouts()
    {
        $this->assertNull($this->my->SetTimeouts(10, 10, 10));
        $this->assertEquals(10, $this->my->getOverlimitTimeout());
    }

    public function testSetUploadProgressCallback()
    {
        $this->my->setUploadProgressCallback('foo');
    }

    public function testSetDownloadProgressCallback()
    {
        $this->my->setDownloadProgressCallback('bar');
    }

    public function test_read_cb()
    {
        $fp = fopen($this->nullFile, 'r');
        $this->assertEmpty($this->my->_read_cb(NULL, $fp, 1024));
        fclose($fp);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\HttpUrlError
     */
    public function test_write_cb()
    {
        $ch = curl_init('file:' . $this->nullFile);
        $len = $this->my->_write_cb($ch, 'FOOBAR');
        $this->assertEquals(6, $len);
    }

    public function testExportCredentials()
    {
        $this->my->Authenticate();
        $arr = $this->my->ExportCredentials();

        $this->assertTrue(is_array($arr));
        $this->assertTrue(is_array($arr['catalog']));
    }

    public function testImportCredentials()
    {
        $this->my->Authenticate();
        $arr = $this->my->ExportCredentials();
        $conn = new StubConnection(self::TEST_DOMAIN, array(
            'username' => 'Foo', 
            'password' => 'Bar'
        ));
        $conn->ImportCredentials($arr);
        $this->assertEquals($arr['token'], $conn->Token());
    }

    public function testObjectStore()
    {
        $objs = $this->my->ObjectStore(
            'cloudFiles', array('DFW'), 'publicURL'
        );
        $this->assertInstanceOf('OpenCloud\ObjectStore\Service', $objs);
    }

    public function testCompute1()
    {
        $comp = $this->my->Compute(
            'cloudServersOpenStack', array('DFW'), 'publicURL'
        );
        $this->assertInstanceOf('OpenCloud\Compute\Service', $comp);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\ServiceValueError
     */
    public function testCompute2()
    {
        $comp = $this->my->Compute();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ServiceValueError
     */
    public function testFailWithoutServiceName()
    {
        $this->my->setDefault('NewService', array('region' => 'DFW', 'name' => '', 'urltype' => ''));
        $this->my->service('NewService');
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ServiceValueError
     */
    public function testFailWithoutServiceUrlType()
    {
        $this->my->setDefault('NewService', array('region' => 'DFW', 'name' => '', 'urltype' => ''));
        $this->my->service('NewService', 'name');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\EndpointError
     */
    public function testComputeFail()
    {
        $comp = $this->my->Compute(
            'FOOBAR', array('DFW'), 'publicURL'
        );
        $this->assertInstanceOf('OpenCloud\Compute\Service', $comp);
    }

    public function testVolumeService()
    {
        $cbs = $this->my->VolumeService('cloudBlockStorage', array('DFW'));
        $this->assertInstanceOf('OpenCloud\Volume\Service', $cbs);
    }

    public function testServiceList()
    {
        $list = $this->my->ServiceList();
        while ($item = $list->Next()) {
            $this->assertInstanceOf('OpenCloud\Common\ServiceCatalogItem', $item);
            break;
        }
    }

}
