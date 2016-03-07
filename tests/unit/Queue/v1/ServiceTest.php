<?php

namespace Rackspace\Test\Queue\v1;

use OpenStack\Test\TestCase;
use Rackspace\Queue\v1\Api;
use Rackspace\Queue\v1\Service;

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