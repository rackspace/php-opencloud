<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStore\v1;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\ObjectStore\v1\Api;
use Rackspace\ObjectStore\v1\Models\Container;

class ContainerTest extends TestCase
{
    /** @var Container */
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->container = new Container($this->client->reveal(), new Api());
        $this->container->name = 'test';
    }

    public function test_populating_sets_quotas()
    {
        $headers  = ['X-Container-Meta-Quota-Bytes' => 100, 'X-Container-Meta-Quota-Count' => 10];
        $response = new Response(200, $headers, null);

        $this->container->populateFromResponse($response);

        $this->assertEquals(100, $this->container->quotaBytes);
        $this->assertEquals(10, $this->container->quotaCount);
    }

    public function test_setting_read_acl()
    {
        $this->setupMock('POST', 'test', null, ['X-Container-Read' => 'foo, bar, baz'], new Response(204));
        $this->container->setReadAcl(['foo', 'bar', 'baz']);
    }

    public function test_setting_write_acl()
    {
        $this->setupMock('POST', 'test', null, ['X-Container-Write' => 'foo, bar, baz'], new Response(204));
        $this->container->setWriteAcl(['foo', 'bar', 'baz']);
    }

    public function test_setting_bytes_quota()
    {
        $this->setupMock('POST', 'test', null, ['X-Container-Meta-Quota-Bytes' => 100], new Response(204));
        $this->container->setBytesQuota(100);
    }

    public function test_setting_count_quota()
    {
        $this->setupMock('POST', 'test', null, ['X-Container-Meta-Quota-Count' => 10], new Response(204));
        $this->container->setCountQuota(10);
    }

    public function test_enabling_logging()
    {
        $this->setupMock('POST', 'test', null, ['X-Container-Meta-Access-Log-Delivery' => 'True'], new Response(204));
        $this->container->enableLogging();
    }

    public function test_disabling_logging()
    {
        $this->setupMock('POST', 'test', null, ['X-Container-Meta-Access-Log-Delivery' => 'False'], new Response(204));
        $this->container->disableLogging();
    }
}