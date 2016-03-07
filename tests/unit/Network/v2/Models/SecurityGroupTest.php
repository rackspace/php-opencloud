<?php

namespace Rackspace\Test\Network\v2\Models;

use OpenStack\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Models\SecurityGroup;

class SecurityGroupTest extends TestCase
{
    private $securityGroup;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->securityGroup = new SecurityGroup($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_lists()
    {
    }

    public function test_it_deletes()
    {
    }

    public function test_it_retrieves()
    {
    }
}