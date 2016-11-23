<?php declare(strict_types=1);

namespace Rackspace\Test;

use OpenStack\Common\Service\Builder;
use OpenStack\Test\TestCase;
use Rackspace\Rackspace;

class RackspaceTest extends TestCase
{
    private $builder;
    private $rackspace;

    public function setUp()
    {
        $this->builder = $this->prophesize(Builder::class);
        $this->rackspace = new Rackspace([], $this->builder->reveal());
    }

    public function test_it_supports_object_store_v1()
    {
        $this->builder
            ->createService('ObjectStore\\v1', ['catalogName' => 'cloudFiles', 'catalogType' => 'object-store'])
            ->shouldBeCalled()
            ->willReturn($this->prophesize(\Rackspace\ObjectStore\v1\Service::class));

        $this->rackspace->objectStoreV1();
    }

    public function test_it_supports_object_store_cdn_v1()
    {
        $this->builder
            ->createService('ObjectStoreCDN\\v1', ['catalogName' => 'cloudFilesCDN', 'catalogType' => 'rax:object-cdn'])
            ->shouldBeCalled()
            ->willReturn($this->prophesize(\Rackspace\ObjectStoreCDN\v1\Service::class));

        $this->rackspace->objectStoreCDNV1();
    }
}
