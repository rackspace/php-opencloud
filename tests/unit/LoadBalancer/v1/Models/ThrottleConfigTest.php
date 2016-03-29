<?php declare(strict_types=1);

namespace Rackspace\Test\LoadBalancer\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\LoadBalancer\v1\Api;
use Rackspace\LoadBalancer\v1\Models\ThrottleConfig;

class ThrottleConfigTest extends TestCase
{
    private $throttleConfig;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->throttleConfig = new ThrottleConfig($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_updates()
    {
    }

    public function test_it_deletes()
    {
    }

    public function test_it_retrieves()
    {
    }
}