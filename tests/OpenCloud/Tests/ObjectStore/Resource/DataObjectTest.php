<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Tests\ObjectStore\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Common\Constants\Header;
use OpenCloud\ObjectStore\Constants\UrlType;
use OpenCloud\ObjectStore\Exception\ObjectNotEmptyException;
use OpenCloud\Tests\MockSubscriber;
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
     * @expectedException \OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Copy_Fails()
    {
        $this->container->dataObject()->copy(null);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Temp_Url_Fails_With_Incorrect_Method()
    {
        $this->container->dataObject('foobar')->getTemporaryUrl(1000, 'DELETE');
    }

    public function test_Temp_Url_Inherits_Url_Type()
    {
        $service = $this->getClient()->objectStoreService(null, 'IAD', 'internalURL');
        $object = $service->getContainer('foo')->dataObject('bar');

        $this->addMockSubscriber(new Response(204, ['X-Account-Meta-Temp-URL-Key' => 'secret']));

        $tempUrl = $object->getTemporaryUrl(60, 'GET');

        // Check that internal URLs are used
        $this->assertContains('snet-storage', $tempUrl);
    }

    public function test_temp_urls_can_be_forced_to_use_public_urls()
    {
        $service = $this->getClient()->objectStoreService(null, 'IAD', 'internalURL');
        $object = $service->getContainer('foo')->dataObject('bar');

        $this->addMockSubscriber(new Response(204, ['X-Account-Meta-Temp-URL-Key' => 'secret']));

        $tempUrl = $object->getTemporaryUrl(60, 'GET', true);

        // Check that internal URLs are NOT used
        $this->assertNotContains('snet-storage', $tempUrl);

        // Check that the URL contains the required file path
        $this->assertContains('/foo/bar', $tempUrl);
    }

    public function test_Purge()
    {
        $object = $this->container->dataObject('foobar');
        $this->setupCdnContainerMockResponse();
        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $object->purge('test@example.com')
        );
    }

    public function test_Public_Urls()
    {
        $object = $this->container->dataObject('foobar');

        $this->setupCdnContainerMockResponse();
        $this->assertNotNull($object->getPublicUrl());
        $this->assertNotNull($object->getPublicUrl(UrlType::SSL));
        $this->assertNotNull($object->getPublicUrl(UrlType::STREAMING));
        $this->assertNotNull($object->getPublicUrl(UrlType::IOS_STREAMING));
    }

    public function test_Symlink_To()
    {
        $targetName = 'new_container/new_object';
        $this->addMockSubscriber(new Response(200, array(Header::X_OBJECT_MANIFEST => $targetName)));
        $object = $this->container->dataObject('foobar');
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $object->createSymlinkTo($targetName));
        $this->assertEquals($targetName, $object->getManifest());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Symlink_To_Fails_With_NoName()
    {
        $object = $this->container->dataObject()->createSymlinkTo(null);
    }

    /**
     * @expectedException OpenCloud\ObjectStore\Exception\ObjectNotEmptyException
     */
    public function test_Symlink_To_Fails_With_NotEmpty()
    {
        $this->addMockSubscriber(new Response(200, array(Header::CONTENT_LENGTH => 100)));
        $object = $this->container->dataObject('foobar')->createSymlinkTo('new_container/new_object');
    }

    public function test_Symlink_From()
    {
        $symlinkName = 'new_container/new_object';

        // We have to fill the mock response queue to properly get the correct X-Object-Manifest header
        // Container\dataObject( )
        //  - Container\refresh( )
        $this->addMockSubscriber(new Response(200));
        // DataObject\createSymlinkFrom( )
        //  - Container\createRefreshRequest( )
        $this->addMockSubscriber(new Response(200));
        //  - CDNContainer\createRefreshRequest( )
        $this->addMockSubscriber(new Response(200));
        //  - Container\objectExists( )
        $this->addMockSubscriber(new Response(200));
        //  - Container\getPartialObject( )
        $this->addMockSubscriber(new Response(200));
        //  - Container\uploadObject( )
        $this->addMockSubscriber(new Response(200, array(Header::X_OBJECT_MANIFEST => $symlinkName)));

        $object = $this->container->dataObject('foobar')->createSymlinkFrom($symlinkName);
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $object);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Symlink_From_Fails_With_NoName()
    {
        $object = $this->container->dataObject()->createSymlinkFrom(null);
    }

    /**
     * @expectedException OpenCloud\ObjectStore\Exception\ObjectNotEmptyException
     */
    public function test_Symlink_From_Fails_With_NotEmpty()
    {
        // We have to fill the mock response queue to properly get the correct Content-Length header
        // Container\dataObject( )
        //  - Container\refresh( )
        $this->addMockSubscriber(new Response(200));
        // DataObject\createSymlinkFrom( )
        //  - Container\createRefreshRequest( )
        $this->addMockSubscriber(new Response(200));
        //  - Container\objectExists( )
        $this->addMockSubscriber(new Response(200));
        //  - Container\getPartialObject( )
        $this->addMockSubscriber(new Response(200, array(Header::CONTENT_LENGTH => 100)));

        $object = $this->container->dataObject('foobar')->createSymlinkFrom('new_container/new_object');
    }
}
