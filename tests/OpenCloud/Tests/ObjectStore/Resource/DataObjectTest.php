<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\ObjectStore\Resource;

use OpenCloud\ObjectStore\Constants\UrlType;
use OpenCloud\Tests\ObjectStore\ObjectStoreTestCase;

class DataObjectTest extends ObjectStoreTestCase
{

    public function test_Pseudo_Dirs()
    {
        $this->addMockSubscriber($this->makeResponse('[{"subdir": "foo"}]'));
        $list = $this->container->objectList();

        foreach ($list as $object) {
            $this->assertTrue($object->isDirectory());
            $this->assertEquals('foo', $object->getName());
            $this->assertEquals($object->getContainer(), $this->container);
            break;
        }
    }

    /**
     * @mockFile Object
     */
    public function test_Contents()
    {
        $object = $this->container->dataObject('foobar');
        $this->assertEquals('text/html', $object->getContentType());
        $this->assertEquals(512000, $object->getContentLength());
        $this->assertNotNull($object->getEtag());
        
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $object->update());
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $object->delete());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Url_Fails()
    {
        $object = $this->container->dataObject();
        $object->getUrl();
    }

    public function test_Copy()
    {
        $object = $this->container->dataObject('foobar');
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response', 
            $object->copy('/new_container/new_object')
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Copy_Fails()
    {
        $this->container->dataObject()->copy(null);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Temp_Url_Fails_With_Incorrect_Method()
    {
        $this->container->dataObject('foobar')->getTemporaryUrl(1000, 'DELETE');
    }
    
    public function test_Purge()
    {
        $object = $this->container->dataObject('foobar');
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response', 
            $object->purge('test@example.com')
        );
    }
    
    public function test_Public_Urls()
    {
        $object = $this->container->dataObject('foobar');
        
        $this->assertNotNull($object->getPublicUrl());
        $this->assertNotNull($object->getPublicUrl(UrlType::SSL));
        $this->assertNotNull($object->getPublicUrl(UrlType::STREAMING));
        $this->assertNotNull($object->getPublicUrl(UrlType::IOS_STREAMING));
    }
}