<?php

namespace Rackspace\Test\Queue\v1\Models;

use OpenStack\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Queue\v1\Models\Message;

class MessageTest extends TestCase
{
    private $message;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->message = new Message($this->client->reveal(), new Api());
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