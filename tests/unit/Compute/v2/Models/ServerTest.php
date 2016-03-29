<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Models\ImageSchedule;
use Rackspace\Compute\v2\Models\Server;
use Rackspace\Compute\v2\Api;
use Rackspace\Compute\v2\Models\VirtualInterface;
use Rackspace\Compute\v2\Models\VolumeAttachment;

class ServerTest extends TestCase
{
    /** @var Server */
    private $server;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->server = new Server($this->client->reveal(), new Api());
        $this->server->id = 'id';
    }

    public function test_it_updates()
    {
        $expectedJson = ['server' => ['name' => 'foo', 'accessIPv4' => '0.0.0.0', 'accessIPv6' => '2001:4800:7812:514:be76:4eff:fe05:aaed']];
        $this->setupMock('PUT', 'servers/id', $expectedJson, [], new Response(200));

        $this->server->name = 'foo';
        $this->server->accessIPv4 = '0.0.0.0';
        $this->server->accessIPv6 = '2001:4800:7812:514:be76:4eff:fe05:aaed';

        $this->server->update();
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'servers/id', null, [], new Response(204));

        $this->server->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'servers/id', null, [], 'Server');

        $this->server->retrieve();
    }

    public function test_it_gets_metadata()
    {
        $this->setupMock('GET', 'servers/id/metadata', null, [], 'Metadata');

        $this->assertEquals(['Label' => 'Web', 'Version' => '2.1'], $this->server->getMetadata());
    }

    public function test_it_merges_metadata()
    {
        $newMetadata = ['foo' => 'bar'];

        $this->setupMock('POST', 'servers/id/metadata', ['metadata' => $newMetadata], [], 'Metadata');

        $this->server->mergeMetadata($newMetadata);
    }

    public function test_it_resets_metadata()
    {
        $newMetadata = ['foo' => 'bar'];

        $this->setupMock('PUT', 'servers/id/metadata', ['metadata' => $newMetadata], [], 'Metadata');

        $this->server->resetMetadata($newMetadata);
    }

    public function test_it_parses_metadata()
    {
        $md = ['foo' => 'bar'];
        $response = new Response(200, [], json_encode(['metadata' => $md]));
        $this->assertEquals($md, $this->server->parseMetadata($response));
    }

    public function test_it_attaches_volume()
    {
        $options = ['device' => null, 'volumeId' => '4ab50df6-7480-45df-8604-b1ee39fe857c'];

        $expectedJson = ['volumeAttachment' => $options];

        $this->setupMock('POST', 'servers/id/os-volume_attachments', $expectedJson, [], new Response(200));

        $this->server->attachVolume($options);
    }

    public function test_it_detaches_volume()
    {
        $this->setupMock('DELETE', 'servers/id/os-volume_attachments/attachmentId', null, [], new Response(202));

        $this->server->detachVolume('attachmentId');
    }

    public function test_it_lists_volumes()
    {
        $this->client
            ->request('GET', 'servers/id/os-volume_attachments', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('VolumeAttachments'));

        foreach ($this->server->listVolumeAttachments() as $volumeAttachment) {
            $this->assertInstanceOf(VolumeAttachment::class, $volumeAttachment);
        }
    }

    public function test_it_gets_volume()
    {
        $this->assertInstanceOf(VolumeAttachment::class, $this->server->getVolumeAttachment('attachmentId'));
    }

    public function test_it_changes_password()
    {
        $this->setupMock('POST', 'servers/id/action', ['changePassword' => ['adminPass' => 'foo']], [], new Response(202));

        $this->server->changePassword('foo');
    }

    public function test_it_reboots()
    {
        $this->setupMock('POST', 'servers/id/action', ['reboot' => ['type' => 'HARD']], [], new Response(202));

        $this->server->reboot('HARD');
    }

    public function test_it_rebuilds()
    {
        $expectedJson = json_encode([
            'rebuild' => [
                "name"              => "new-server-test",
                "imageRef"          => "d42f821e-c2d1-4796-9f07-af5ed7912d0e",
                "OS-DCF:diskConfig" => "AUTO",
                "adminPass"         => "diane123",
                "metadata"          => ["My Server Name" => "Apache1"],
                "personality"       => [
                    [
                        "path"     => "/etc/banner.txt",
                        "contents" => "ICAgICAgDQoiQSBjbG91ZCBkb2VzIG5vdCBrbm93IHdoeSBp dCBtb3ZlcyBpbiBqdXN0IHN1Y2ggYSBkaXJlY3Rpb24gYW5k IGF0IHN1Y2ggYSBzcGVlZC4uLkl0IGZlZWxzIGFuIGltcHVs c2lvbi4uLnRoaXMgaXMgdGhlIHBsYWNlIHRvIGdvIG5vdy4g QnV0IHRoZSBza3kga25vd3MgdGhlIHJlYXNvbnMgYW5kIHRo ZSBwYXR0ZXJucyBiZWhpbmQgYWxsIGNsb3VkcywgYW5kIHlv dSB3aWxsIGtub3csIHRvbywgd2hlbiB5b3UgbGlmdCB5b3Vy c2VsZiBoaWdoIGVub3VnaCB0byBzZWUgYmV5b25kIGhvcml6 b25zLiINCg0KLVJpY2hhcmQgQmFjaA==",
                    ],
                ],
            ]
        ], JSON_UNESCAPED_SLASHES);

        $this->setupMock('POST', 'servers/id/action', $expectedJson, ["Content-Type" => "application/json"], new Response(202));

        $options = [
            "name"        => "new-server-test",
            "imageId"     => "d42f821e-c2d1-4796-9f07-af5ed7912d0e",
            "diskConfig"  => "AUTO",
            "password"    => "diane123",
            "metadata"    => ["My Server Name" => "Apache1"],
            "personality" => [
                [
                    "path"     => "/etc/banner.txt",
                    "contents" => "ICAgICAgDQoiQSBjbG91ZCBkb2VzIG5vdCBrbm93IHdoeSBp dCBtb3ZlcyBpbiBqdXN0IHN1Y2ggYSBkaXJlY3Rpb24gYW5k IGF0IHN1Y2ggYSBzcGVlZC4uLkl0IGZlZWxzIGFuIGltcHVs c2lvbi4uLnRoaXMgaXMgdGhlIHBsYWNlIHRvIGdvIG5vdy4g QnV0IHRoZSBza3kga25vd3MgdGhlIHJlYXNvbnMgYW5kIHRo ZSBwYXR0ZXJucyBiZWhpbmQgYWxsIGNsb3VkcywgYW5kIHlv dSB3aWxsIGtub3csIHRvbywgd2hlbiB5b3UgbGlmdCB5b3Vy c2VsZiBoaWdoIGVub3VnaCB0byBzZWUgYmV5b25kIGhvcml6 b25zLiINCg0KLVJpY2hhcmQgQmFjaA==",
                ],
            ],
        ];

        $this->server->rebuild($options);
    }

    public function test_it_resizes()
    {
        $expectedJson = ['resize' => ['flavorRef' => '3', "OS-DCF:diskConfig" => 'MANUAL']];

        $this->setupMock('POST', 'servers/id/action', $expectedJson, [], new Response(202));

        $this->server->resize(['flavorId' => '3', 'diskConfig' => 'MANUAL']);
    }

    public function test_it_confirms_resize()
    {
        $this->setupMock('POST', 'servers/id/action', ['confirmResize' => null], [], new Response(202));

        $this->server->confirmResize();
    }

    public function test_it_reverts_resize()
    {
        $this->setupMock('POST', 'servers/id/action', ['revertResize' => null], [], new Response(202));

        $this->server->revertResize();
    }

    public function test_it_creates_image()
    {
        $options = ['name' => 'testImage', 'metadata' => ['foo' => 'bar']];

        $this->setupMock('POST', 'servers/id/action', ['createImage' => $options], [], new Response(202));

        $this->server->createImage($options);
    }

    public function test_it_rescues()
    {
        $this->setupMock('POST', 'servers/id/action', ['rescue' => 'none'], [], new Response(202));

        $this->server->rescue();
    }

    public function test_it_rescues_with_image_ref()
    {
        $this->setupMock('POST', 'servers/id/action', ['rescue' => ['rescue_image_ref' => 'foo']], [], new Response(202));

        $this->server->rescue('foo');
    }

    public function test_it_unrescues()
    {
        $this->setupMock('POST', 'servers/id/action', ['unrescue' => null], [], new Response(202));

        $this->server->unrescue();
    }

    public function test_it_lists_addresses()
    {
        $this->setupMock('GET', 'servers/id/ips', null, [], 'IpAddresses');

        $expected = [
            'public' => [
                ['version' => 6, 'addr' => '2001:4800:7817:0104:7e32:e3ee:ff04:930f'],
                ['version' => 4, 'addr' => '23.253.107.140'],
            ],
            'private' => [
                ['version' => 4, 'addr' => '10.208.196.170']
            ],
        ];

        $this->assertEquals($expected, $this->server->getIpAddresses());
    }

    public function test_it_lists_addresses_for_specific_network()
    {
        $this->setupMock('GET', 'servers/id/ips/networkId', null, [], 'IpAddresses');

        $expected = [
            'public' => [
                ['version' => 6, 'addr' => '2001:4800:7817:0104:7e32:e3ee:ff04:930f'],
                ['version' => 4, 'addr' => '23.253.107.140'],
            ],
            'private' => [
                ['version' => 4, 'addr' => '10.208.196.170']
            ],
        ];

        $this->assertEquals($expected, $this->server->getIpAddresses('networkId'));
    }

    public function test_it_enables_daily_images()
    {
        $expectedJson = ['image_schedule' => ['retention' => 5]];
        $this->setupMock('POST', 'servers/id/rax-si-image-schedule', $expectedJson, [], new Response(202));

        $this->server->enableScheduledImages(['retention' => 5]);
    }

    public function test_it_enables_weekly_images()
    {
        $expectedJson = ['image_schedule' => ['retention' => 5, 'day_of_week' => 'MONDAY']];
        $this->setupMock('POST', 'servers/id/rax-si-image-schedule', $expectedJson, [], new Response(202));

        $this->server->enableScheduledImages(['retention' => 5, 'dayOfWeek' => 'MONDAY']);
    }

    public function test_it_gets_scheduled_images()
    {
        $this->setupMock('GET', 'servers/id/rax-si-image-schedule', null, [], 'ScheduledImage');

        $is = (new ImageSchedule($this->client->reveal(), new Api()))
            ->populateFromResponse($this->getFixture('ScheduledImage'));

        $this->assertEquals($is, $this->server->getScheduledImages());
    }

    public function test_it_disables_scheduled_images()
    {
        $this->setupMock('DELETE', 'servers/id/rax-si-image-schedule', null, [], new Response(202));

        $this->server->disableScheduledImages();
    }

    public function test_it_lists_virtual_interfaces()
    {
        $this->client
            ->request('GET', 'servers/id/os-virtual-interfacesv2', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('VirtualInterfaces'));

        foreach ($this->server->listVirtualInterfaces() as $virtualInterface) {
            $this->assertInstanceOf(VirtualInterface::class, $virtualInterface);
        }
    }

    public function test_it_creates_virtual_interface()
    {
        $expectedJson = ['virtual_interface' => ['network_id' => '1f7920d3-0e63-4fec-a1cb-f7916671e8eb']];

        $this->setupMock('POST', 'servers/id/os-virtual-interfacesv2', $expectedJson, [], 'VirtualInterfaces');

        $options = ['networkId' => '1f7920d3-0e63-4fec-a1cb-f7916671e8eb'];

        $this->assertInstanceOf(VirtualInterface::class, $this->server->createVirtualInterface($options));
    }

    public function test_it_deletes_virtual_interface()
    {
        $this->setupMock('DELETE', 'servers/id/os-virtual-interfacesv2/aid', null, [], new Response(202));

        $this->server->deleteVirtualInterface('aid');
    }
}