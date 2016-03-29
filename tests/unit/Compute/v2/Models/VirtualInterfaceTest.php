<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use Rackspace\Compute\v2\Api;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Models\VirtualInterface;

class VirtualInterfaceTest extends TestCase
{
    private $virtualInterface;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->virtualInterface = new VirtualInterface($this->client->reveal(), new Api());
    }

    public function test_class()
    {
        $this->assertInstanceOf(VirtualInterface::class, $this->virtualInterface);
    }
}