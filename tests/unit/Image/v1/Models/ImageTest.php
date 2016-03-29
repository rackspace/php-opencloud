<?php declare(strict_types=1);

namespace Rackspace\Test\Image\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Image\v1\Api;
use Rackspace\Image\v1\Models\Image;

class ImageTest extends TestCase
{
    private $image;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->image = new Image($this->client->reveal(), new Api());
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