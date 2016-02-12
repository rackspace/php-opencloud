<?php

namespace Rackspace\Test\Database\v1\Models;

use OpenStack\Test\TestCase;
use Rackspace\Database\v1\Api;
use Rackspace\Database\v1\Models\Datastore;

class DatastoreTest extends TestCase
{
    private $datastore;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->datastore = new Datastore($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}