<?php declare(strict_types=1);

namespace Rackspace\Test\RackConnect\v3\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\RackConnect\v3\Models\LoadBalancerPool;

class LoadBalancerPoolTest extends TestCase
{
    private $loadBalancerPool;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->loadBalancerPool = new LoadBalancerPool($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}