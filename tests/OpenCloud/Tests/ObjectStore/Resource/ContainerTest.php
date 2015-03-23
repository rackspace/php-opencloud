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
use OpenCloud\Common\Constants\Size;
use OpenCloud\Tests\ObjectStore\ObjectStoreTestCase;
use OpenCloud\ObjectStore\Enum\ReturnType;

class ContainerTest extends ObjectStoreTestCase
{
    private function getFilePath()
    {
        $path = '/tmp/php_sdk_test_file';
        if (!file_exists($path)) {
            file_put_contents($path, '.');
        }

        return $path;
    }

    public function test_Services()
    {
        $this->assertInstanceOf(
            'OpenCloud\ObjectStore\CDNService',
            $this->container->getCDNService()
        );
    }

    public function test_Get_Container()
    {
        $container = $this->container;

        $this->assertEquals('foo', $container->getName());
        $this->assertEquals('5', $container->getObjectCount());
        $this->assertEquals('3846773', $container->getBytesUsed());
        $this->assertFalse($container->hasLogRetention());

        $this->setupCdnContainerMockResponse();

        $cdn = $container->getCdn();
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\CDNContainer', $cdn);
        $this->assertEquals('tx82a6752e00424edb9c46fa2573132e2c', $cdn->getTransId());
        $this->assertFalse($cdn->hasLogRetention());
        $this->assertTrue($cdn->isCdnEnabled());

        $this->assertEquals(
            'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
            $cdn->getCdnSslUri()
        );
        $this->assertEquals(
            'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            $cdn->getCdnUri()
        );
        $this->assertEquals('259200', $cdn->getTtl());
        $this->assertEquals(
            'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
            $cdn->getCdnStreamingUri()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\NoNameError
     */
    public function test_Bad_Name_Url()
    {
        $container = $this->container;
        $container->name = '';

        $container->getUrl();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CdnNotAvailableError
     */
    public function test_NonCDN_Container()
    {
        $this->addMockSubscriber($this->makeResponse(null, 200));
        $this->addMockSubscriber($this->makeResponse(null, 404));

        $container = $this->service->getContainer('foo');
        $container->getCdn();
    }

    public function test_Delete()
    {
        $container = $this->container;

        $this->addMockSubscriber(new Response(204, array('X-Container-Object-Count' => 5)));
        $this->addMockSubscriber($this->makeResponse('[]', 200));

        $response = $container->delete(true);
        $this->isResponse($response);
    }

    /**
     * @expectedException \OpenCloud\ObjectStore\Exception\ContainerException
     */
    public function test_Delete_NonEmpty_Container()
    {
        $container = $this->container;
        $this->addMockSubscriber($this->makeResponse('[]', 409));
        $container->delete();
    }

    public function test_Object_List()
    {
        $container = $this->container;

        $this->addMockSubscriber($this->makeResponse('[{"name":"test_obj_1","hash":"4281c348eaf83e70ddce0e07221c3d28","bytes":14,"content_type":"application\/octet-stream","last_modified":"2009-02-03T05:26:32.612278"},{"name":"test_obj_2","hash":"b039efe731ad111bc1b0ef221c3849d0","bytes":64,"content_type":"application\/octet-stream","last_modified":"2009-02-03T05:26:32.612278"}]', 200));

        $list = $container->objectList();
        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);
        $this->assertEquals('test_obj_1', $list->first()->getName());
    }

    public function test_Misc_Operations()
    {
        $container = $this->container;

        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $container->enableLogging()
        );

        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $container->disableLogging()
        );

        $this->setupCdnContainerMockResponse();

        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $container->getCdn()->setStaticIndexPage('index.html')
        );

        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $container->getCdn()->setStaticErrorPage('error.html')
        );

        $this->assertInstanceOf(
            'Guzzle\Http\Message\Response',
            $container->disableCdn()
        );

        $container->enableCdn(500);
    }

    public function test_Get_Object()
    {
        $this->addMockSubscriber($this->makeResponse('b0dffe8254d152d8fd28f3c5e0404a10'));
        $object = $this->container->getObject('foobar');

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $object);
        $this->assertEquals(
            'b0dffe8254d152d8fd28f3c5e0404a10',
            (string)$object->getContent()
        );
        $this->assertEquals('foobar', $object->getName());
    }

    /**
     * @expectedException \OpenCloud\ObjectStore\Exception\ObjectNotFoundException
     */
    public function test_Get_Object_404()
    {
        $this->addMockSubscriber(new Response(404));
        $this->container->getObject('foobar');
    }

    /**
     * @expectedException \Guzzle\Http\Exception\BadResponseException
     */
    public function test_Get_Object_500()
    {
        $this->addMockSubscriber(new Response(500));
        $this->container->getObject('foobar');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Upload_Multiple_Fails_Without_Name()
    {
        $container = $this->container;
        $container->uploadObjects(array(
            array('path' => '/foo')
        ));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Upload_Multiple_Fails_With_No_Data()
    {
        $container = $this->container;
        $container->uploadObjects(array(
            array('name' => 'test', 'baz' => 'something')
        ));
    }

    public function test_Upload_Multiple()
    {
        $container = $this->container;

        $responses = $container->uploadObjects(array(
            array('name' => 'test', 'body' => 'FOOBAR')
        ));
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $responses[0]);

        $container->uploadObjects(array(
            array('name' => 'test', 'path' => $this->getFilePath())
        ));
    }

    public function test_Upload_Multiple_Return_DataObject_Array()
    {
        $tempFileName = tempnam(sys_get_temp_dir(), "php-opencloud-test-");

        $tempFile = fopen($tempFileName, 'w+');
        fwrite($tempFile, 'BAZQUX');

        $container = $this->container;

        $dataObjects = $container->uploadObjects(array(
            array('name' => 'test1', 'body' => 'FOOBAR'),
            array('name' => 'test2', 'path' => $tempFileName),
            array('name' => 'test2', 'body' => 'BARBAR')
        ), array(), ReturnType::DATA_OBJECT_ARRAY);
        fclose($tempFile);
        unlink($tempFileName);

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $dataObjects[0]);
        $this->assertEquals('test1', $dataObjects[0]->getName());
        $this->assertInstanceOf('Guzzle\Http\EntityBody', $dataObjects[0]->getContent());
        $this->assertEquals('FOOBAR', (string) $dataObjects[0]->getContent());

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $dataObjects[1]);
        $this->assertEquals('test2', $dataObjects[1]->getName());
        $this->assertInstanceOf('Guzzle\Http\EntityBody', $dataObjects[1]->getContent());
        $this->assertEquals('BAZQUX', (string) $dataObjects[1]->getContent());

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $dataObjects[2]);
        $this->assertEquals('test2', $dataObjects[2]->getName());
        $this->assertInstanceOf('Guzzle\Http\EntityBody', $dataObjects[2]->getContent());
        $this->assertEquals('BARBAR', (string) $dataObjects[2]->getContent());
    }

    public function test_Upload()
    {
        $this->assertInstanceOf(
            'OpenCloud\ObjectStore\Resource\DataObject',
            $this->container->uploadObject('foobar', 'data')
        );
    }

    public function test_Large_Upload()
    {
        $options = array(
            'name'        => 'new_object',
            'path'        => $this->getFilePath(),
            'metadata'    => array('author' => 'Jamie'),
            'partSize'    => Size::MB * 20,
            'concurrency' => 3,
            'progress'    => function ($options) {
                    var_dump($options);
                }
        );

        $container = $this->container;
        $container->setupObjectTransfer($options);
    }

    public function test_Large_Upload_With_Body()
    {
        $options = array(
            'name' => 'new_object',
            'body' => 'foo'
        );

        $container = $this->container;
        $container->setupObjectTransfer($options);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Large_Upload_Fails_Without_Name()
    {
        $options = array(
            'path' => '/foo'
        );

        $container = $this->container;
        $container->setupObjectTransfer($options);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Large_Upload_Fails_Without_Entity()
    {
        $options = array(
            'name' => 'new_object',
            'path' => '/' . rand(1, 9999)
        );

        $container = $this->container;
        $container->setupObjectTransfer($options);
    }

    public function test_Metadata()
    {
        $metadata = $this->container->getMetadata();

        $this->assertEquals('Whaling', $metadata->getProperty('Subject'));
        $this->assertEquals(
            $this->container->getMetadata()->getProperty('Subject'),
            $metadata->getProperty('Subject')
        );

        $response = $this->container->unsetMetadataItem('Subject');
    }

    public function test_Quotas()
    {
        $container = $this->container;

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $container->setCountQuota(50));
        $this->assertInstanceOf('Guzzle\Http\Message\Response', $container->setBytesQuota(50 * 1024));

        $this->assertEquals(50, $container->getCountQuota());
        $this->assertEquals(50 * 1024, $container->getBytesQuota());
    }

    /**
     * @mockFile Object_Metadata
     */
    public function test_Partial_Object()
    {
        $object = $this->container->getPartialObject('test.foo');

        $this->assertEquals('', (string)$object->getContent());
        $this->assertNotNull($object->getLastModified());
    }

    /**
     * @mockFile Object_Metadata
     */
    public function test_Object_Exists_True()
    {
        $this->assertTrue($this->container->objectExists('test.foo'));
    }

    /**
     * @mockFile Object_Metadata_404
     */
    public function test_Object_Exists_False()
    {
        $this->assertFalse($this->container->objectExists('test.foo'));
    }
}
