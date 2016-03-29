<?php declare(strict_types=1);

namespace Rackspace\Test\RackConnect\v3;

use OpenCloud\Test\TestCase;
use Rackspace\RackConnect\v3\Api;
use Rackspace\RackConnect\v3\Service;

class ServiceTest extends TestCase
{
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = __DIR__;

        $this->service = new Service($this->client->reveal(), new Api());
    }

    public function test_it_extends()
    {
        $this->assertInstanceOf(Service::class, $this->service);
    }
}