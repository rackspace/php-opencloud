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
} 