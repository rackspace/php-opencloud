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

namespace OpenCloud\Tests\Image;

use OpenCloud\Tests\OpenCloudTestCase;

class ServiceTest extends OpenCloudTestCase
{
    public function setupObjects()
    {
        $this->service = $this->getClient()->imageService('cloudImages', 'IAD');
    }

    public function test_Image_List()
    {
        $this->addMockSubscriber($this->makeResponse('{"images":[{"id":"da3b75d9-3f4a-40e7-8a2c-bfab23927dea","name":"cirros-0.3.0-x86_64-uec-ramdisk","status":"active","visibility":"public","size":2254249,"checksum":"2cec138d7dae2aa59038ef8c9aec2390","tags":["ping","pong"],"created_at":"2012-08-10T19:23:50Z","updated_at":"2012-08-10T19:23:50Z","self":"/v2/images/da3b75d9-3f4a-40e7-8a2c-bfab23927dea","file":"/v2/images/da3b75d9-3f4a-40e7-8a2c-bfab23927dea/file","schema":"/v2/schemas/image"},{"id":"0d5bcbc7-b066-4217-83f4-7111a60a399a","name":"cirros-0.3.0-x86_64-uec","status":"active","visibility":"public","size":25165824,"checksum":"2f81976cae15c16ef0010c51e3a6c163","tags":[],"created_at":"2012-08-10T19:23:50Z","updated_at":"2012-08-10T19:23:50Z","self":"/v2/images/0d5bcbc7-b066-4217-83f4-7111a60a399a","file":"/v2/images/0d5bcbc7-b066-4217-83f4-7111a60a399a/file","schema":"/v2/schemas/image"},{"id":"e6421c88-b1ed-4407-8824-b57298249091","name":"cirros-0.3.0-x86_64-uec-kernel","status":"active","visibility":"public","size":4731440,"checksum":"cfb203e7267a28e435dbcb05af5910a9","tags":[],"created_at":"2012-08-10T19:23:49Z","updated_at":"2012-08-10T19:23:49Z","self":"/v2/images/e6421c88-b1ed-4407-8824-b57298249091","file":"/v2/images/e6421c88-b1ed-4407-8824-b57298249091/file","schema":"/v2/schemas/image"}],"first":"/v2/images?limit=3","next":"/v2/images?limit=3&marker=e6421c88-b1ed-4407-8824-b57298249091","schema":"/v2/schemas/images"}'));

        $images = $this->service->listImages();
        $this->assertInstanceOf('OpenCloud\Common\Collection\PaginatedIterator', $images);
        $this->assertInstanceOf('OpenCloud\Image\Resource\Image', $images->getElement(0));
    }

    public function test_Get_Image()
    {
        $this->assertInstanceOf('OpenCloud\Image\Resource\Image', $this->service->getImage('foo'));
    }

    public function test_Images_Schema()
    {
        $this->assertInstanceOf('OpenCloud\Image\Resource\Schema\Schema', $this->service->getImagesSchema());
    }

    public function test_Image_Schema()
    {
        $this->assertInstanceOf('OpenCloud\Image\Resource\Schema\Schema', $this->service->getImageSchema());
    }

    public function test_Members_Schema()
    {
        $this->assertInstanceOf('OpenCloud\Image\Resource\Schema\Schema', $this->service->getMembersSchema());
    }

    public function test_Member_Schema()
    {
        $this->assertInstanceOf('OpenCloud\Image\Resource\Schema\Schema', $this->service->getMemberSchema());
    }
}
