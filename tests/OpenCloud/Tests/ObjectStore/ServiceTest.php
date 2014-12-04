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

/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */
namespace OpenCloud\Tests\ObjectStore;

use Guzzle\Http\Message\Response;
use OpenCloud\ObjectStore\Constants\UrlType;
use OpenCloud\ObjectStore\Service;

class ServiceTest extends ObjectStoreTestCase
{
    public function test__construct()
    {
        $service = $this->getClient()->objectStoreService('cloudFiles', 'DFW');
        $this->assertInstanceOf('OpenCloud\ObjectStore\Service', $service);
        $this->assertInstanceOf('OpenCloud\ObjectStore\CDNService', $service->getCdnService());
    }

    public function test_Url_Secret()
    {
        $account = $this->service->getAccount();
        $account->setTempUrlSecret('foo');
        $this->assertEquals('foo', $account->getTempUrlSecret());

        // Random val
        $account->setTempUrlSecret();
        $temp = $account->getTempUrlSecret();
        $this->assertTrue($temp && $temp != 'foo');
    }

    public function test_List_Containers()
    {
        $this->addMockSubscriber($this->makeResponse('[{"name":"test_container_1", "count":2, "bytes":78},{"name":"test_container_2", "count":1, "bytes":17}]'));

        $list = $this->service->listContainers();

        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);
        $this->assertEquals('test_container_1', $list->first()->getName());

        $this->addMockSubscriber($this->makeResponse('[{"name":"1"},{"name":"1"},{"name":"1"},{"name":"1"},{"name":"1"}]'));
        $partialList = $this->service->listContainers(array('limit' => 5));
        $this->assertEquals(5, $partialList->count());
    }

    public function test_Create_Container()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));

        $container = $this->service->createContainer('fooBar');

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $container);

        $this->assertFalse($this->service->createContainer('existing-container'));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bad_Container_Name_Empty()
    {
        $this->service->createContainer('');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bad_Container_Name_Slashes()
    {
        $this->service->createContainer('foo/bar');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bad_Container_Name_Long()
    {
        $this->service->createContainer(str_repeat('a', Service::MAX_CONTAINER_NAME_LENGTH + 1));
    }

    public function test_Bulk_Extract()
    {
        $response = $this->service->bulkExtract('fooBarContainer', 'CONTENT', UrlType::TAR);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @expectedException OpenCloud\ObjectStore\Exception\BulkOperationException
     */
    public function test_Bad_Bulk_Extract()
    {
        $this->addMockSubscriber($this->makeResponse('{"Number Files Created":10,"Response Status":"400 Bad Request","Errors":[["/v1/AUTH_test/test_cont/big_file.wav","413 Request Entity Too Large"]],"Response Body":""}'));
        $this->service->bulkExtract('bad-container', 'CONTENT', UrlType::TAR);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Bulk_Extract_With_Incorrect_Type()
    {
        $this->service->bulkExtract('bad-container', 'CONTENT', 'foo');
    }

    public function test_Bulk_Delete()
    {
        $this->addMockSubscriber($this->makeResponse(null, 202));
        $response = $this->service->bulkDelete(array('foo/Bar', 'foo/Baz'));
        $this->assertEquals(202, $response->getStatusCode());
    }

    /**
     * @expectedException OpenCloud\ObjectStore\Exception\BulkOperationException
     */
    public function test_Bad_Bulk_Delete()
    {
        $this->addMockSubscriber($this->makeResponse('{"Number Not Found":0,"Response Status":"400 Bad Request","Errors":[["/v1/AUTH_test/non_empty_container","409 Conflict"]],"Number Deleted":0,"Response Body":""}'));
        $this->service->bulkDelete(array('nonEmptyContainer'));
    }

    public function test_Batch_Delete_Returns_Array_Of_Responses()
    {
        $responses = array_fill(0, 2, new Response(200));

        foreach ($responses as $response) {
            $this->addMockSubscriber($response);
        }

        $paths = array_fill(0, 15000, '/foo/bar');

        $this->assertEquals($responses, $this->service->batchDelete($paths));
    }

    public function test_Accounts()
    {
        $account = $this->service->getAccount();

        $this->assertInstanceOf('OpenCloud\Common\Metadata', $account->getDetails());
    }
}
