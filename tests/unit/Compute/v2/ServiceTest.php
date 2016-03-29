<?php declare(strict_types=1);

namespace Rackspace\Test\Compute\v2;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Compute\v2\Api;
use Rackspace\Compute\v2\Models\Flavor;
use Rackspace\Compute\v2\Models\Image;
use Rackspace\Compute\v2\Models\Keypair;
use Rackspace\Compute\v2\Models\Network;
use Rackspace\Compute\v2\Models\Server;
use Rackspace\Compute\v2\Service;

class ServiceTest extends TestCase
{
    /** @var Service */
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = __DIR__;

        $this->service = new Service($this->client->reveal(), new Api());
    }

    /**
     * @covers \Rackspace\Compute\v2\Models\Server::create
     */
    public function test_it_creates_server()
    {
        $expectedJson = [
            "server" => [
                "name"              => "api-test-server-1",
                "imageRef"          => "3afe97b2-26dc-49c5-a2cc-a2fc8d80c001",
                "flavorRef"         => "2",
                "config_drive"      => true,
                "OS-DCF:diskConfig" => "AUTO",
                "metadata"          => [
                    "My Server Name" => "API Test Server 1",
                ],
                "networks"          => [
                    ["uuid" => "00000000-0000-0000-0000-000000000000"],
                    ["uuid" => "11111111-1111-1111-1111-111111111111"],
                ],
            ],
        ];

        $this->setupMock('POST', 'servers', $expectedJson, [], new Response(202));

        $options = [
            'name'        => 'api-test-server-1',
            'imageId'     => '3afe97b2-26dc-49c5-a2cc-a2fc8d80c001',
            'flavorId'    => '2',
            'configDrive' => true,
            'diskConfig'  => 'AUTO',
            'metadata'    => [
                'My Server Name' => 'API Test Server 1',
            ],
            'networks'    => [
                ["uuid" => "00000000-0000-0000-0000-000000000000"],
                ["uuid" => "11111111-1111-1111-1111-111111111111"],
            ],
        ];

        $this->service->createServer($options);
    }

    public function test_it_creates_bfv()
    {
        $expectedJson = [
            "server" => [
                "name"                    => "bfv-server-1",
                "flavorRef"               => "2",
                'max_count'               => 1,
                'min_count'               => 1,
                'block_device_mapping_v2' => [
                    [
                        'boot_index'            => 0,
                        'uuid'                  => 'bb02b1a3-bc77-4d17-ab5b-421d89850fca',
                        'volume_size'           => 100,
                        'source_type'           => 'image',
                        'destination_type'      => 'volume',
                        'delete_on_termination' => true,
                    ],
                ],
                "networks"                => [
                    ["uuid" => "00000000-0000-0000-0000-000000000000"],
                    ["uuid" => "11111111-1111-1111-1111-111111111111"],
                ],
            ],
        ];

        $this->setupMock('POST', 'servers', $expectedJson, [], new Response(202));

        $options = [
            'name'         => 'bfv-server-1',
            'flavorId'     => '2',
            'maxCount'     => 1,
            'minCount'     => 1,
            'blockDevices' => [
                [
                    'bootIndex'           => 0,
                    'uuid'                => 'bb02b1a3-bc77-4d17-ab5b-421d89850fca',
                    'volumeSize'          => 100,
                    'sourceType'          => 'image',
                    'destinationType'     => 'volume',
                    'deleteOnTermination' => true,
                ],
            ],
            'networks'     => [
                ["uuid" => "00000000-0000-0000-0000-000000000000"],
                ["uuid" => "11111111-1111-1111-1111-111111111111"],
            ],
        ];

        $this->service->createServer($options);
    }

    public function test_it_creates_server_with_disk_config()
    {
        $expectedJson = json_encode([
            "server" => [
                "name"              => "config-server-1",
                "flavorRef"         => "2",
                'imageRef'          => '3afe97b2-26dc-49c5-a2cc-a2fc8d80c001',
                "config_drive"      => true,
                "OS-DCF:diskConfig" => "AUTO",
                "personality"       => [
                    [
                        "path"     => "/etc/banner.txt",
                        "contents" => "ICAgICAgDQoiQSBjbG91ZCBkb2VzIG5vdCBrbm93IHdoeSBpdCBtb3ZlcyBpbiBqdXN0IHN1Y2ggYSBkaXJlY3Rpb24gYW5kIGF0IHN1Y2ggYSBzcGVlZC4uLkl0IGZlZWxzIGFuIGltcHVsc2lvbi4uLnRoaXMgaXMgdGhlIHBsYWNlIHRvIGdvIG5vdy4gQnV0IHRoZSBza3kga25vd3MgdGhlIHJlYXNvbnMgYW5kIHRoZSBwYXR0ZXJucyBiZWhpbmQgYWxsIGNsb3VkcywgYW5kIHlvdSB3aWxsIGtub3csIHRvbywgd2hlbiB5b3UgbGlmdCB5b3Vyc2VsZiBoaWdoIGVub3VnaCB0byBzZWUgYmV5b25kIGhvcml6b25zLiINCg0KLVJpY2hhcmQgQmFjaA==",
                    ],
                ],
                "networks"          => [
                    ["uuid" => "4ebd35cf-bfe7-4d93-b0d8-eb468ce2245a"],
                    ["uuid" => "00000000-0000-0000-0000-000000000000"],
                    ["uuid" => "11111111-1111-1111-1111-111111111111"],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES);

        $this->setupMock('POST', 'servers', $expectedJson, ['Content-Type' => 'application/json'], new Response(202));

        $options = [
            'name'        => 'config-server-1',
            'flavorId'    => '2',
            'imageId'     => '3afe97b2-26dc-49c5-a2cc-a2fc8d80c001',
            'configDrive' => true,
            'diskConfig'  => 'AUTO',
            "personality" => [
                [
                    "path"     => "/etc/banner.txt",
                    "contents" => "ICAgICAgDQoiQSBjbG91ZCBkb2VzIG5vdCBrbm93IHdoeSBpdCBtb3ZlcyBpbiBqdXN0IHN1Y2ggYSBkaXJlY3Rpb24gYW5kIGF0IHN1Y2ggYSBzcGVlZC4uLkl0IGZlZWxzIGFuIGltcHVsc2lvbi4uLnRoaXMgaXMgdGhlIHBsYWNlIHRvIGdvIG5vdy4gQnV0IHRoZSBza3kga25vd3MgdGhlIHJlYXNvbnMgYW5kIHRoZSBwYXR0ZXJucyBiZWhpbmQgYWxsIGNsb3VkcywgYW5kIHlvdSB3aWxsIGtub3csIHRvbywgd2hlbiB5b3UgbGlmdCB5b3Vyc2VsZiBoaWdoIGVub3VnaCB0byBzZWUgYmV5b25kIGhvcml6b25zLiINCg0KLVJpY2hhcmQgQmFjaA==",
                ],
            ],
            'networks'    => [
                ["uuid" => "4ebd35cf-bfe7-4d93-b0d8-eb468ce2245a"],
                ["uuid" => "00000000-0000-0000-0000-000000000000"],
                ["uuid" => "11111111-1111-1111-1111-111111111111"],
            ],
        ];

        $this->service->createServer($options);
    }

    public function test_it_lists_servers()
    {
        $this->client
            ->request('GET', 'servers', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Servers'));

        foreach ($this->service->listServers() as $s) {
            $this->assertInstanceOf(Server::class, $s);
        }
    }

    public function test_it_lists_servers_with_details()
    {
        $this->client
            ->request('GET', 'servers/detail', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('ServersDetail'));

        foreach ($this->service->listServers(true) as $s) {
            /** @var Server $s */
            $this->assertInstanceOf(Server::class, $s);
            $this->assertNotNull($s->accessIPv4);
            $this->assertNotNull($s->progress);
            $this->assertNotNull($s->powerState);
        }
    }

    public function test_it_get_server()
    {
        $this->assertInstanceOf(Server::class, $this->service->getServer('id'));
    }

    public function test_it_creates_keypair()
    {
        $expectedJson = ['keypair' => ["name" => "name_of_keypair",]];

        $this->setupMock('POST', 'os-keypairs', $expectedJson, [], new Response(200));

        $this->service->createKeypair(["name" => "name_of_keypair"]);
    }

    public function test_it_imports_keypair()
    {
        $expectedJson = json_encode(['keypair' => [
            "name"       => "name_of_keypair",
            'public_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDx8nkQv/zgGgB4rMYmIf+6A4l6Rr+o/6lHBQdW5aYd44bd8JttDCE/F/pNRr0lRE+PiqSPO8nDPHw0010JeMH9gYgnnFlyY3/OcJ02RhIPyyxYpv9FhY+2YiUkpwFOcLImyrxEsYXpD/0d3ac30bNH6Sw9JD9UZHYcpSxsIbECHw',
        ]], JSON_UNESCAPED_SLASHES);

        $this->setupMock('POST', 'os-keypairs', $expectedJson, ["Content-Type" => "application/json"], new Response(200));

        $this->service->createKeypair([
            "name"      => "name_of_keypair",
            'publicKey' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDx8nkQv/zgGgB4rMYmIf+6A4l6Rr+o/6lHBQdW5aYd44bd8JttDCE/F/pNRr0lRE+PiqSPO8nDPHw0010JeMH9gYgnnFlyY3/OcJ02RhIPyyxYpv9FhY+2YiUkpwFOcLImyrxEsYXpD/0d3ac30bNH6Sw9JD9UZHYcpSxsIbECHw',
        ]);
    }

    public function test_it_lists_keypairs()
    {
        $this->client
            ->request('GET', 'os-keypairs', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Keypairs'));

        foreach ($this->service->listKeypairs() as $kp) {
            $this->assertInstanceOf(Keypair::class, $kp);
        }
    }

    public function test_it_gets_keypair()
    {
        $this->assertInstanceOf(Keypair::class, $this->service->getKeypair('name'));
    }

    public function test_it_lists_flavors()
    {
        $this->client
            ->request('GET', 'flavors', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Flavors'));

        foreach ($this->service->listFlavors() as $f) {
            $this->assertInstanceOf(Flavor::class, $f);
        }
    }

    public function test_it_lists_flavors_with_details()
    {
        $this->client
            ->request('GET', 'flavors/detail', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('FlavorsDetail'));

        foreach ($this->service->listFlavors(true) as $f) {
            /** @var Flavor $f */
            $this->assertInstanceOf(Flavor::class, $f);
            $this->assertNotNull($f->ram);
            $this->assertNotNull($f->vcpus);
            $this->assertNotNull($f->rxtxFactor);
        }
    }

    public function test_it_gets_flavor()
    {
        $this->assertInstanceOf(Flavor::class, $this->service->getFlavor('id'));
    }

    public function test_it_lists_images()
    {
        $this->client
            ->request('GET', 'images', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Images'));

        foreach ($this->service->listImages() as $i) {
            $this->assertInstanceOf(Image::class, $i);
        }
    }

    public function test_it_lists_images_with_details()
    {
        $this->client
            ->request('GET', 'images/detail', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('ImagesDetail'));

        foreach ($this->service->listImages(true) as $i) {
            /** @var Image $i */
            $this->assertInstanceOf(Image::class, $i);
            $this->assertNotNull($i->created);
            $this->assertNotNull($i->minRam);
            $this->assertNotNull($i->minDisk);
        }
    }

    public function test_it_gets_image()
    {
        $this->assertInstanceOf(Image::class, $this->service->getImage('id'));
    }

    public function test_it_lists_networks()
    {
        $this->client
            ->request('GET', 'os-networksv2', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Networks'));

        foreach ($this->service->listNetworks() as $n) {
            $this->assertInstanceOf(Network::class, $n);
        }
    }

    public function test_it_creates_network()
    {
        $options = ['cidr' => '192.168.0.0/24', 'label' => 'superprivate'];

        $expectedJson = json_encode(['network' => $options], JSON_UNESCAPED_SLASHES);
        $this->setupMock('POST', 'os-networksv2', $expectedJson, ["Content-Type" => "application/json"], new Response(200));

        $this->service->createNetwork($options);
    }

    public function test_it_gets_network()
    {
        $this->assertInstanceOf(Network::class, $this->service->getNetwork('id'));
    }
}