<?php

namespace Rackspace\Test\Compute\v2\Models;

use OpenStack\Test\TestCase;

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
