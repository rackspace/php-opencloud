<?php declare(strict_types=1);

namespace Rackspace\Test\Monitoring\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Monitoring\v1\Api;
use Rackspace\Monitoring\v1\Models\NotificationPlan;

class NotificationPlanTest extends TestCase
{
    private $notificationPlan;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->notificationPlan = new NotificationPlan($this->client->reveal(), new Api());
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