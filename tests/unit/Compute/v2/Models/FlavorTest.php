<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Api;
use Rackspace\Compute\v2\Models\Flavor;

class FlavorTest extends TestCase
{
    private $flavor;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->flavor = new Flavor($this->client->reveal(), new Api());
        $this->flavor->id = 'id';
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'flavors/id', null, [], 'Flavor');

        $this->flavor->retrieve();
    }

    public function test_it_lists_extra_specs()
    {
        $this->setupMock('GET', 'flavors/id/os-extra_specs', null, [], 'ExtraSpecs');

        $expected = [
            "policy_class"         => "general_flavor",
            "class"                => "general1",
            "disk_io_index"        => 40,
            "number_of_data_disks" => 0,
        ];

        $this->assertEquals($expected, $this->flavor->retrieveExtraSpecs());
    }
}