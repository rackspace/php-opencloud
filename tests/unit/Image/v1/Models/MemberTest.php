<?php declare(strict_types=1);

namespace Rackspace\Test\Image\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Image\v1\Api;
use Rackspace\Image\v1\Models\Member;

class MemberTest extends TestCase
{
    private $member;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->member = new Member($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_updates()
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