<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStoreCDN;

use OpenCloud\Test\TestCase;
use Rackspace\ObjectStoreCDN\v1\Api;
use Rackspace\ObjectStoreCDN\v1\Models\Container;
use Rackspace\ObjectStoreCDN\v1\Service;

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


    public function test_it_lists_containers()
    {
        $this->client
            ->request('GET', '', ['query' => ['format' => 'json'], 'headers' => []])
            ->shouldBeCalled()
            ->willReturn($this->getFixture('GET_Containers'));

        foreach ($this->service->listContainers() as $container) {
            /** @var Container $container */
            $this->assertInstanceOf(Container::class, $container);
            $this->assertTrue($container->isCdnEnabled());

            $this->assertEquals(
                'http://acc3b9ba6a79805f5577-e7e60117100ffd73b45850c0b1fd96c1.iosr.cf5.rackcdn.com',
                (string) $container->iosUri
            );

            $this->assertEquals(
                'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
                (string) $container->sslUri
            );

            $this->assertEquals(
                'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
                (string) $container->streamingUri
            );

            $this->assertEquals(
                'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
                (string) $container->cdnUri
            );

            $this->assertFalse($container->isCdnLoggingEnabled());
            $this->assertEquals('cdn_test1', $container->name);
            $this->assertEquals(259200, $container->ttl);

            break;
        }
    }

    public function test_it_gets_container()
    {
        $container = $this->service->getContainer('test');
        $this->assertInstanceOf(Container::class, $container);
    }
}