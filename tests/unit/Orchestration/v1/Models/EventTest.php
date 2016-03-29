<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Orchestration\v1\Models\Event;

class EventTest extends TestCase
{
    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->event = new Event($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}