<?php

namespace Rackspace\Test\DNS\v1;

use OpenStack\Test\TestCase;
use Rackspace\DNS\v1\Api;
use Rackspace\DNS\v1\Service;

class ServiceTest extends TestCase
{
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = __DIR__;

        $this->service = new Service($this->client->reveal(), new Api());
    }
}