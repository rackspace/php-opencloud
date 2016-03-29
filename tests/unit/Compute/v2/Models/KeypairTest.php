<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Api;
use Rackspace\Compute\v2\Models\Keypair;

class KeypairTest extends TestCase
{
    private $keypair;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->keypair = new Keypair($this->client->reveal(), new Api());
        $this->keypair->name = 'id';
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'os-keypairs/id', null, [], new Response(202));

        $this->keypair->delete();
    }
}