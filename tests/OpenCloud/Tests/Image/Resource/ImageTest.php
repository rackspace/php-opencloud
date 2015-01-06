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

namespace OpenCloud\Tests\Image\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Image\Resource\Image;
use OpenCloud\Image\Resource\Schema\Schema;
use OpenCloud\Tests\OpenCloudTestCase;

class PublicImage extends Image
{
    public static function getPatchHeaders()
    {
        return parent::getPatchHeaders();
    }
}

class ImageTest extends OpenCloudTestCase
{
    public function setupObjects()
    {
        $this->image = new PublicImage($this->getClient()->imageService('cloudImages', 'IAD'));
    }

    protected function getSchemaData()
    {
        return json_decode(file_get_contents(__DIR__ . '/Schema/image.json'), true);
    }

    public function test_Update()
    {
        $schema = Schema::factory($this->getSchemaData());
        $config = array(
            'visibility' => 'private',
            'name'       => 'FOOBAR'
        );

        $response = $this->image->update($config, $schema);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    public function test_Updating_AddProperty()
    {
        $schema = Schema::factory($this->getSchemaData());
        $config = array(
            'foo' => 'bar'
        );

        $response = $this->image->update($config, $schema);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_Update_Fails_With_AddProperties()
    {
        $data = $this->getSchemaData();
        unset($data['additionalProperties']);

        $schema = Schema::factory($data);
        $config = array(
            'foo' => 'bar'
        );

        $response = $this->image->update($config, $schema);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    public function test_Refresh()
    {
        $this->addMockSubscriber($this->makeResponse('{"id":"da3b75d9-3f4a-40e7-8a2c-bfab23927dea","name":"cirros-0.3.0-x86_64-uec-ramdisk","status":"active","visibility":"public","size":2254249,"checksum":"2cec138d7dae2aa59038ef8c9aec2390","tags":["ping","pong"],"created_at":"2012-08-10T19:23:50Z","updated_at":"2012-08-10T19:23:50Z","self":"/v2/images/da3b75d9-3f4a-40e7-8a2c-bfab23927dea","file":"/v2/images/da3b75d9-3f4a-40e7-8a2c-bfab23927dea/file","schema":"/v2/schemas/image"}'));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->image->refresh());
    }

    public function test_Delete()
    {
        $this->addMockSubscriber(new Response(204));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->image->delete());
    }

    public function test_List_Members()
    {
        $json = <<<EOT
{"members":[{"created_at":"2013-10-07T17:58:03Z","image_id":"dbc999e3-c52f-4200-bedd-3b18fe7f87fe","member_id":"123456789","schema":"/v2/schemas/member","status":"pending","updated_at":"2013-10-07T17:58:03Z"},{"created_at":"2013-10-07T17:58:55Z","image_id":"dbc999e3-c52f-4200-bedd-3b18fe7f87fe","member_id":"987654321","schema":"/v2/schemas/member","status":"accepted","updated_at":"2013-10-08T12:08:55Z"}],"schema":"/v2/schemas/members"}
EOT;

        $this->addMockSubscriber($this->makeResponse($json));

        $members = $this->image->listMembers();

        $this->assertInstanceOf('OpenCloud\Common\Collection\PaginatedIterator', $members);
        $this->assertInstanceOf('OpenCloud\Image\Resource\Member', $members->getElement(0));
    }

    public function test_Get_Member()
    {
        $this->addMockSubscriber(new Response(200));

        $this->assertInstanceOf('OpenCloud\Image\Resource\Member', $this->image->getMember('foo'));
    }

    public function test_Create_Member()
    {
        $this->addMockSubscriber(new Response(201));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->image->createMember(12345));
    }

    public function test_Delete_Member()
    {
        $this->addMockSubscriber(new Response(201));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->image->deleteMember(12345));
    }

    public function test_Create_Tag()
    {
        $this->addMockSubscriber(new Response(201));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->image->addTag(12345));
    }

    public function test_Delete_Tag()
    {
        $this->addMockSubscriber(new Response(204));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $this->image->deleteTag(12345));
    }

    public function testGetPatchHeaders()
    {
        $expectedHeaders = array(
            'Content-Type' => 'application/openstack-images-v2.1-json-patch'
        );

        $image = $this->image;
        $this->assertEquals($expectedHeaders, $image::getPatchHeaders());
    }
}
