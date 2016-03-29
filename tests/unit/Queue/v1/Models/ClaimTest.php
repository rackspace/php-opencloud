<?php declare(strict_types=1);

namespace Rackspace\Test\Queue\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Queue\v1\Models\Claim;

class ClaimTest extends TestCase
{
    private $claim;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->claim = new Claim($this->client->reveal(), new Api());
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
}