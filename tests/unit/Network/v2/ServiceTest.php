<?php

namespace Rackspace\Test\Network\v2;

use OpenStack\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Service;

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