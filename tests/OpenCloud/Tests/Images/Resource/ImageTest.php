<?php

namespace OpenCloud\Tests\Images\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Images\Resource\Image;
use OpenCloud\Images\Resource\Schema\Schema;
use OpenCloud\Tests\OpenCloudTestCase;

class ImageTest extends OpenCloudTestCase
{
    protected function getSchemaData()
    {
        return json_decode(file_get_contents(__DIR__ . '/Schema/image.json'), true);
    }

    public function test_Update()
    {
        $this->addMockSubscriber(new Response(200));

        $image = new Image($this->getClient()->imageService('cloudImages', 'IAD'));

        $schema = Schema::factory($this->getSchemaData());
        $config = array(
            'visibility' => 'private',
            'name'       => 'FOOBAR'
        );

        $response = $image->update($config, $schema);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    public function test_Updating_AddProperty()
    {
        $this->addMockSubscriber(new Response(200));

        $image = new Image($this->getClient()->imageService('cloudImages', 'IAD'));

        $schema = Schema::factory($this->getSchemaData());
        $config = array(
            'foo' => 'bar'
        );

        $response = $image->update($config, $schema);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_Update_Fails_With_AddProperties()
    {
        $this->addMockSubscriber(new Response(200));

        $image = new Image($this->getClient()->imageService('cloudImages', 'IAD'));

        $data = $this->getSchemaData();
        unset($data['additionalProperties']);

        $schema = Schema::factory($data);
        $config = array(
            'foo' => 'bar'
        );

        $response = $image->update($config, $schema);

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $response);
    }

    public function test_Refresh()
    {
        $this->addMockSubscriber($this->makeResponse('{"id":"da3b75d9-3f4a-40e7-8a2c-bfab23927dea","name":"cirros-0.3.0-x86_64-uec-ramdisk","status":"active","visibility":"public","size":2254249,"checksum":"2cec138d7dae2aa59038ef8c9aec2390","tags":["ping","pong"],"created_at":"2012-08-10T19:23:50Z","updated_at":"2012-08-10T19:23:50Z","self":"/v2/images/da3b75d9-3f4a-40e7-8a2c-bfab23927dea","file":"/v2/images/da3b75d9-3f4a-40e7-8a2c-bfab23927dea/file","schema":"/v2/schemas/image"}'));

        $image = new Image($this->getClient()->imageService('cloudImages', 'IAD'));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $image->refresh());
    }

    public function test_Delete()
    {
        $this->addMockSubscriber(new Response(200));

        $image = new Image($this->getClient()->imageService('cloudImages', 'IAD'));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $image->delete());
    }
} 