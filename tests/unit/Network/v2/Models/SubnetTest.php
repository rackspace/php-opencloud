<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Models\Subnet;

class SubnetTest extends TestCase
{
    private $subnet;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->subnet = new Subnet($this->client->reveal(), new Api());
        $this->subnet->id = 'id';
    }

    public function test_it_updates()
    {
        $expectedJson = ['subnet' => ['name' => 'new_name']];

        $this->setupMock('PUT', 'subnets/id', $expectedJson, [], new Response(200));

        $this->subnet->name = 'new_name';
        $this->subnet->update();
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'subnets/id', null, [], new Response(202));

        $this->subnet->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'subnets/id', null, [], 'Network');

        $this->subnet->retrieve();
    }
}