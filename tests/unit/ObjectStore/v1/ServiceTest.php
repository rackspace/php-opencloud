<?php

namespace Rackspace\Test\ObjectStore\v1;

use OpenStack\Test\TestCase;
use Rackspace\ObjectStore\v1\Api;
use Rackspace\ObjectStore\v1\Service;

class ServiceTest extends TestCase
{
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = __DIR__;

        $this->service = new Service($this->client->reveal(), new Api());
    }

    public function test_extends_openstack()
    {
        $this->assertInstanceOf(\OpenStack\ObjectStore\v1\Service::class, $this->service);
    }
}