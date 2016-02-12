<?php

namespace Rackspace\Test\Compute\v2\Models;

use OpenStack\Compute\v2\Api;
use OpenStack\Test\TestCase;
use Rackspace\Compute\v2\Models\Image;

class ImageTest extends TestCase
{
    private $image;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->image = new Image($this->client->reveal(), new Api());
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

    public function test_it_gets_metadata()
    {
    }

    public function test_it_merges_metadata()
    {
    }

    public function test_it_resets_metadata()
    {
    }

    public function test_it_parses_metadata()
    {
    }
}