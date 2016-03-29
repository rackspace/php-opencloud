<?php declare(strict_types=1);

namespace Rackspace\Test\DNS\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\DNS\v1\Api;
use Rackspace\DNS\v1\Models\Subdomain;

class SubdomainTest extends TestCase
{
    private $subdomain;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->subdomain = new Subdomain($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }
}