<?php

namespace Rackspace\Test\CDN\v1;

use OpenStack\Test\TestCase;
use Rackspace\CDN\v1\Api;
use Rackspace\CDN\v1\Service;

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