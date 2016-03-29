<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStoreCDN\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\ObjectStoreCDN\v1\Api;
use Rackspace\ObjectStoreCDN\v1\Models\Object;

class ObjectTest extends TestCase
{
    /** @var Object */
    private $object;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->object = new Object($this->client->reveal(), new Api());
        $this->object->containerName = 'foo';
        $this->object->name = 'bar';
    }

    public function test_can_be_deleted()
    {
        $this->setupMock('DELETE', 'foo/bar', null, [], new Response(204));

        $this->object->delete();
    }
}