<?php declare(strict_types=1);

namespace Rackspace\Test\CDN\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\CDN\v1\Api;
use Rackspace\CDN\v1\Models\Flavor;

class FlavorTest extends TestCase
{
    private $flavor;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->flavor = new Flavor($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}