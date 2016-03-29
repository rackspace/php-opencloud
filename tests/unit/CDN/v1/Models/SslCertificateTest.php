<?php declare(strict_types=1);

namespace Rackspace\Test\CDN\v1\Models;

use OpenCloud\Test\TestCase;
use Rackspace\CDN\v1\Api;
use Rackspace\CDN\v1\Models\SslCertificate;

class SslCertificateTest extends TestCase
{
    private $sslCertificate;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->sslCertificate = new SslCertificate($this->client->reveal(), new Api());
    }

    public function test_it_creates()
    {
    }

    public function test_it_deletes()
    {
    }
}