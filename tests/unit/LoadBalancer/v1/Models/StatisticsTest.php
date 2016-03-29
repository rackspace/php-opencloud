<?php declare(strict_types=1);

namespace Rackspace\Test\LoadBalancer\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\LoadBalancer\v1\Api;
use Rackspace\LoadBalancer\v1\Models\Statistics;

class StatisticsTest extends TestCase
{
    private $statistics;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->statistics = new Statistics($this->client->reveal(), new Api());
    }

    public function test_it_retrieves()
    {
    }
}
