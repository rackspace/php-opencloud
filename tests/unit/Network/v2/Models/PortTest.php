<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Models\Port;

class PortTest extends TestCase
{
    /** @var  Port */
    private $port;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->port = new Port($this->client->reveal(), new Api());
        $this->port->id = 'id';
    }

    public function test_it_updates()
    {
        $expectedJson = ['port' => ['name' => 'new_name']];

        $this->setupMock('PUT', 'ports/id', $expectedJson, [], new Response(200));

        $this->port->name = 'new_name';
        $this->port->update();
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'ports/id', null, [], new Response(202));

        $this->port->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'ports/id', null, [], 'Network');

        $this->port->retrieve();
    }
}