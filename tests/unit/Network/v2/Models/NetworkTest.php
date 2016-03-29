<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Models\Network;

class NetworkTest extends TestCase
{
    /** @var Network */
    private $network;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->network = new Network($this->client->reveal(), new Api());
        $this->network->id = 'id';
    }

    public function test_it_updates()
    {
        $expectedJson = ['network' => ['name' => 'new_name']];

        $this->setupMock('PUT', 'networks/id', $expectedJson, [], new Response(200));

        $this->network->name = 'new_name';
        $this->network->update();
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'networks/id', null, [], new Response(202));

        $this->network->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'networks/id', null, [], 'Network');

        $this->network->retrieve();
    }
}