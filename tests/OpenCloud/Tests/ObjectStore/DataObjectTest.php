<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\ObjectStore;

use OpenCloud\ObjectStore\Constants\UrlType;

class DataObjectTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    public function __construct()
    {
        $this->service = $this->getClient()->objectStoreService('cloudFiles', 'DFW');  
    }
    
    public function test_Pseudo_Dirs()
    {
        $container = $this->service->getContainer('container2');
        $list = $container->objectList();
        while ($object = $list->next()) {
            $this->assertTrue($object->isDirectory());
            $this->assertEquals($object->getContainer(), $container);
            break;
        }
    }
    
    public function test_Contents()
    {
        $object = $this->service->getContainer('container1')->dataObject('foobar');
        $this->assertEquals('text/plain', $object->getContentType());
        $this->assertEquals(32, $object->getContentLength());
        $this->assertNotNull($object->getEtag());
        
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $object->update());
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $object->delete());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Url_Fails()
    {
        $object = $this->service->getContainer('container1')->dataObject();
        $object->getUrl();
    }

    public function test_Copy()
    {
        $object = $this->service->getContainer('container1')->dataObject('foobar');
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response', 
            $object->copy('/new_container/new_object')
        );

        $new = $this->service->getContainer('container2')->dataObject('foobar');
        $object = $this->service->getContainer('container1')->dataObject('foobar');
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response', 
            $object->copy($new)
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Copy_Fails()
    {
        $this->service->getContainer('container1')->dataObject()->copy(null);
    }
    
    public function test_Temp_Url()
    {
        $this->service->getAccount()->setTempUrlSecret('lalalala');
        
        $object = $this->service->getContainer('container1')->dataObject('foobar');
        $this->assertNotNull($object->getTemporaryUrl(1000, 'GET'));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Temp_Url_Fails_With_Incorrect_Method()
    {
        $this->service
            ->getContainer('container1')
            ->dataObject('foobar')
            ->getTemporaryUrl(1000, 'DELETE');
    }
    
    public function test_Purge()
    {
        $object = $this->service->getContainer('container1')->dataObject('foobar');
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response', 
            $object->purge('test@example.com')
        );
    }
    
    public function test_Public_Urls()
    {
        $object = $this->service->getContainer('container1')->dataObject('foobar');
        
        $this->assertNotNull($object->getPublicUrl());
        $this->assertNotNull($object->getPublicUrl(UrlType::SSL));
        $this->assertNotNull($object->getPublicUrl(UrlType::STREAMING));
        $this->assertNotNull($object->getPublicUrl(UrlType::IOS_STREAMING));
    }
    
}