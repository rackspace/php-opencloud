<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Models\Network;
use Rackspace\Network\v2\Models\Port;
use Rackspace\Network\v2\Models\SecurityGroup;
use Rackspace\Network\v2\Models\Subnet;
use Rackspace\Network\v2\Service;

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

    public function test_it_lists_networks()
    {
        $this->client
            ->request('GET', 'networks', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Networks'));

        foreach ($this->service->listNetworks() as $n) {
            /** @var $n Network */
            $this->assertInstanceOf(Network::class, $n);

            $this->assertTrue($n->adminStateUp);
            $this->assertNotNull($n->id);
            $this->assertNotNull($n->name);
            $this->assertInternalType('boolean', $n->shared);
            $this->assertInternalType('string', $n->status);
            $this->assertInternalType('array', $n->subnets);
            $this->assertInternalType('string', $n->tenantId);
        }
    }

    public function test_it_creates_network()
    {
        $options = ['name' => 'test', 'shared' => false];
        $expectedJson = ['network' => $options];

        $this->setupMock('POST', 'networks', $expectedJson, [], new Response(201));

        $n = $this->service->createNetwork($options);
        $this->assertInstanceOf(Network::class, $n);
    }

    public function test_it_gets_network()
    {
        $this->assertInstanceOf(Network::class, $this->service->getNetwork('id'));
    }

    public function test_it_lists_subnets()
    {
        $this->client
            ->request('GET', 'subnets', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Subnets'));

        foreach ($this->service->listSubnets() as $s) {
            /** @var $s Subnet */
            $this->assertInstanceOf(Subnet::class, $s);

            $this->assertNotEmpty($s->allocationPools);
            $this->assertNotEmpty($s->cidr);
            $this->assertFalse($s->enableDhcp);
            $this->assertNull($s->gatewayIp);
            $this->assertEmpty($s->hostRoutes);
            $this->assertNotEmpty($s->id);
            $this->assertNotEmpty($s->ipVersion);
            $this->assertNotEmpty($s->name);
            $this->assertNotEmpty($s->networkId);
            $this->assertNotEmpty($s->tenantId);
        }
    }

    public function test_it_creates_subnet()
    {
        $options = [
            'name'      => 'test',
            'networkId' => 'd32019d3-bc6e-4319-9c1d-6722fc136a22',
            'ipVersion' => 4,
            'cidr'      => '192.168.199.0/24',
        ];

        $expectedJson = json_encode(['subnet' => [
            'name'       => 'test',
            'network_id' => 'd32019d3-bc6e-4319-9c1d-6722fc136a22',
            'ip_version' => 4,
            'cidr'       => '192.168.199.0/24',
        ]], JSON_UNESCAPED_SLASHES);

        $this->setupMock('POST', 'subnets', $expectedJson, ["Content-Type" => "application/json"], new Response(201));

        $n = $this->service->createSubnet($options);
        $this->assertInstanceOf(Subnet::class, $n);
    }

    public function test_it_gets_subnet()
    {
        $this->assertInstanceOf(Subnet::class, $this->service->getSubnet('id'));
    }

    public function test_it_lists_ports()
    {
        $this->client
            ->request('GET', 'ports', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('Ports'));

        foreach ($this->service->listPorts() as $p) {
            /** @var $p Port */
            $this->assertInstanceOf(Port::class, $p);

            $this->assertTrue($p->adminStateUp);
            $this->assertEmpty($p->deviceId);
            $this->assertNull($p->deviceOwner);
            $this->assertNotNull($p->fixedIps);
            $this->assertEquals('10ba23f5-bb70-4fd7-a118-83f89b62e340', $p->id);
            $this->assertEquals('BE:CB:FE:00:00:EE', $p->macAddress);
            $this->assertEquals('port1', $p->name);
            $this->assertEquals('6406ed30-193a-4958-aae5-7c05268d332b', $p->networkId);
            $this->assertEmpty($p->securityGroups);
            $this->assertEquals('ACTIVE', $p->status);
        }
    }

    public function test_it_creates_port()
    {
        $options = [
            'name'         => 'port1',
            'adminStateUp' => true,
            'deviceId'     => 'd6b4d3a5-c700-476f-b609-1493dd9dadc0',
            'networkId'    => '6aeaf34a-c482-4bd3-9dc3-7faf36412f12',
        ];

        $expectedJson = ['port' => [
            'admin_state_up' => true,
            'device_id'      => 'd6b4d3a5-c700-476f-b609-1493dd9dadc0',
            'name'           => 'port1',
            'network_id'     => '6aeaf34a-c482-4bd3-9dc3-7faf36412f12',
        ]];

        $this->setupMock('POST', 'ports', $expectedJson, [], new Response(201));

        $n = $this->service->createPort($options);
        $this->assertInstanceOf(Port::class, $n);
    }

    public function test_it_gets_port()
    {
        $this->assertInstanceOf(Port::class, $this->service->getPort('id'));
    }

    public function test_it_lists_secgroups()
    {
        $this->client
            ->request('GET', 'security-groups', ['headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('SecurityGroups'));

        foreach ($this->service->listSecurityGroups() as $sg) {
            /** @var $sg SecurityGroup */
            $this->assertInstanceOf(SecurityGroup::class, $sg);

            $this->assertEquals('default', $sg->name);
            $this->assertEquals('default', $sg->description);
            $this->assertEquals('85cc3048-abc3-43cc-89b3-377341426ac5', $sg->id);
            $this->assertCount(2, $sg->securityGroupRules);
        }
    }

    public function test_it_creates_secgroup()
    {
        $options = [
            'name'        => 'new-webservers',
            'description' => 'security group for webservers',
        ];

        $expectedJson = ['security_group' => $options];

        $this->setupMock('POST', 'security-groups', $expectedJson, [], new Response(201));

        $n = $this->service->createSecurityGroup($options);
        $this->assertInstanceOf(SecurityGroup::class, $n);
    }

    public function test_it_gets_secgroup()
    {
        $this->assertInstanceOf(SecurityGroup::class, $this->service->getSecurityGroup('id'));
    }
}