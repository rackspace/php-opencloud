<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use GuzzleHttp\Psr7\Response;
use Rackspace\Compute\v2\Api;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Models\Network;

class NetworkTest extends TestCase
{
    private $network;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->network = new Network($this->client->reveal(), new Api());
        $this->network->id = 'id';
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'os-networksv2/id', null, [], new Response(202));

        $this->network->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'os-networksv2/id', null, [], new Response(200));

        $this->network->retrieve();
    }
}