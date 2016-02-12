<?php

namespace Rackspace\Test\Compute\v2\Models;

use OpenStack\Compute\v2\Api;
use OpenStack\Test\TestCase;
use Rackspace\Compute\v2\Models\Keypair;

class KeypairTest extends TestCase
{
    private $keypair;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->keypair = new Keypair($this->client->reveal(), new Api());
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
}