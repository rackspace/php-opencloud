<?php declare(strict_types=1);

namespace Rackspace\Test\Image\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Image\v1\Api;
use Rackspace\Image\v1\Models\Task;

class TaskTest extends TestCase
{
    private $task;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->task = new Task($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}