<?php declare(strict_types=1);

namespace Rackspace\Test\LoadBalancer\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\LoadBalancer\v1\Api;
use Rackspace\LoadBalancer\v1\Models\VirtualIp;

class VirtualIpTest extends TestCase
{
    private $virtualIp;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->virtualIp = new VirtualIp($this->client->reveal(), new Api());
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
