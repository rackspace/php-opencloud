<?php

namespace Rackspace\Test\Orchestration\v1;

use OpenStack\Test\TestCase;
use Rackspace\Orchestration\v1\Api;
use Rackspace\Orchestration\v1\Service;

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