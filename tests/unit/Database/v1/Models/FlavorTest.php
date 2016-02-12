<?php

namespace Rackspace\Test\Database\v1\Models;

use OpenStack\Compute\v2\Models\Flavor;
use OpenStack\Test\TestCase;
use Rackspace\Database\v1\Api;

class FlavorTest extends TestCase
{
    private $flavor;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->flavor = new Flavor($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}