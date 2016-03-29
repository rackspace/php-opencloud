<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use GuzzleHttp\Psr7\Response;
use Rackspace\Compute\v2\Api;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Models\VolumeAttachment;

class VolumeAttachmentTest extends TestCase
{
    private $volumeAttachment;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->volumeAttachment = new VolumeAttachment($this->client->reveal(), new Api());
        $this->volumeAttachment->id = 'id';
        $this->volumeAttachment->serverId = 'serverId';
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'servers/serverId/os-volume_attachments/id', null, [], new Response(200));

        $this->volumeAttachment->retrieve();
    }
}