<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use GuzzleHttp\Psr7\Response;
use Rackspace\Compute\v2\Api;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Models\Image;

class ImageTest extends TestCase
{
    private $image;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->image = new Image($this->client->reveal(), new Api());
        $this->image->id = 'id';
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'images/id', null, [], new Response(202));

        $this->image->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'images/id', null, [], 'Image');

        $this->image->retrieve();
    }

    public function test_it_gets_metadata()
    {
        $this->setupMock('GET', 'images/id/metadata', null, [], 'Metadata');

        $this->assertInternalType('array', $this->image->getMetadata());
    }

    public function test_it_merges_metadata()
    {
        $options = ['foo' => 'bar'];

        $this->setupMock('POST', 'images/id/metadata', ['metadata' => $options], [], 'Metadata');

        $this->image->mergeMetadata($options);
    }

    public function test_it_resets_metadata()
    {
        $body = json_encode(['metadata' => ['foo' => 1, 'bar' => 2]]);
        $response = new Response(200, ['Content-Type' => 'application/json'], $body);
        $this->setupMock('GET', 'images/id/metadata', null, [], $response);

        $this->setupMock('POST', 'images/id/metadata', ['metadata' => ['foo' => 10, 'baz' => 3]], [], 'Metadata');
        $this->setupMock('DELETE', 'images/id/metadata/bar', null, [], new Response(200));

        $this->image->resetMetadata(['foo' => 10, 'baz' => 3]);
    }

    public function test_it_parses_metadata()
    {
        $md = ['foo' => 1, 'bar' => 2];

        $body = json_encode(['metadata' => $md]);
        $response = new Response(200, ['Content-Type' => 'application/json'], $body);

        $this->assertEquals($md, $this->image->parseMetadata($response));
    }
}