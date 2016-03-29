<?php declare(strict_types=1);

namespace Rackspace\Test\Monitoring\v1;

use OpenCloud\Test\TestCase;
use Rackspace\Monitoring\v1\Api;
use Rackspace\Monitoring\v1\Service;

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