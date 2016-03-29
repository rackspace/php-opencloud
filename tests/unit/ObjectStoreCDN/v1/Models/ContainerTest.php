<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStoreCDN\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\ObjectStoreCDN\v1\Api;
use Rackspace\ObjectStoreCDN\v1\Models\Container;
use Rackspace\ObjectStoreCDN\v1\Models\Object;

class ContainerTest extends TestCase
{
    /** @var Container */
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->container = new Container($this->client->reveal(), new Api());
        $this->container->name = 'cdn_test1';
    }

    public function test_it_populates_from_response()
    {
        $response = $this->getFixture('HEAD_Container');
        $this->container->populateFromResponse($response);

        $this->assertTrue($this->container->isCdnEnabled());

        $this->assertEquals(
            'http://acc3b9ba6a79805f5577-e7e60117100ffd73b45850c0b1fd96c1.iosr.cf5.rackcdn.com',
            (string) $this->container->iosUri
        );

        $this->assertEquals(
            'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
            (string) $this->container->sslUri
        );

        $this->assertEquals(
            'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
            (string) $this->container->streamingUri
        );

        $this->assertEquals(
            'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            (string) $this->container->cdnUri
        );

        $this->assertFalse($this->container->isCdnLoggingEnabled());
        $this->assertEquals(259200, $this->container->ttl);
    }

    public function test_it_retrieves()
    {
        $this->setupMock('HEAD', 'cdn_test1', null, [], 'HEAD_Container');

        $this->container->retrieve();
    }

    public function test_it_sends_request_if_cdn_status_cannot_be_established()
    {
        $this->setupMock('HEAD', 'cdn_test1', null, [], 'HEAD_Container');

        $this->assertTrue($this->container->isCdnEnabled());
    }

    public function test_it_sends_request_if_log_retention_cannot_be_established()
    {
        $this->setupMock('HEAD', 'cdn_test1', null, [], 'HEAD_Container');

        $this->assertFalse($this->container->isCdnLoggingEnabled());
    }

    public function test_it_enables_cdn()
    {
        $this->setupMock('PUT', 'cdn_test1', null, ['X-CDN-Enabled' => 'True'], new Response(204));

        $this->container->enableCdn();
    }

    public function test_it_disables_cdn()
    {
        $this->setupMock('POST', 'cdn_test1', null, ['X-CDN-Enabled' => 'False'], new Response(204));

        $this->container->disableCdn();
    }

    public function test_it_enables_logging()
    {
        $this->setupMock('POST', 'cdn_test1', null, ['X-Log-Retention' => 'True'], new Response(204));

        $this->container->enableCdnLogging();
    }

    public function test_it_disables_logging()
    {
        $this->setupMock('POST', 'cdn_test1', null, ['X-Log-Retention' => 'False'], new Response(204));

        $this->container->disableCdnLogging();
    }

    public function test_it_retrieves_object()
    {
        $object = $this->container->getObject('foo');
        $this->assertInstanceOf(Object::class, $object);
    }
}