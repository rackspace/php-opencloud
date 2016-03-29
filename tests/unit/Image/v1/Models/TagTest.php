<?php declare(strict_types=1);

namespace Rackspace\Test\Image\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Image\v1\Api;
use Rackspace\Image\v1\Models\Tag;

class TagTest extends TestCase
{
    private $tag;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->tag = new Tag($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_deletes()
    {
    }
}