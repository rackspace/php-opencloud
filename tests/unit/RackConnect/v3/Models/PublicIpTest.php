<?php declare(strict_types=1);

namespace Rackspace\Test\RackConnect\v3\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\RackConnect\v3\Models\PublicIp;

class PublicIpTest extends TestCase
{
    private $publicIp;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->publicIp = new PublicIp($this->client->reveal(), new Api());
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