<?php

namespace Rackspace\Test\Monitoring\v1\Models;

use OpenStack\Test\TestCase;
use Rackspace\Monitoring\v1\Api;
use Rackspace\Monitoring\v1\Models\CheckType;

class CheckTypeTest extends TestCase
{

    private $checkType;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->checkType = new CheckType($this->client->reveal(), new Api());
    }

    public function test_it_lists()
    {
    }

    public function test_it_retrieves()
    {
    }
}