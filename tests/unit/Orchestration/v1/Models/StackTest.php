<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Orchestration\v1\Models\Stack;

class StackTest extends TestCase
{
    private $stack;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->stack = new Stack($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_updates()
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