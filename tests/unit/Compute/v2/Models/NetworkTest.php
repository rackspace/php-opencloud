<?php

namespace Rackspace\Test\Compute\v2\Models;

use OpenStack\Compute\v2\Api;
use OpenStack\Test\TestCase;
use Rackspace\Compute\v2\Models\Network;

class NetworkTest extends TestCase
{
    private $network;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->network = new Network($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_lists()
    {
    }

    public function test_it_deletes()
    {
    }

    public function test_it_retrieves()
    {
    }
}