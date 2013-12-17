<?php


namespace OpenCloud\Tests\Identity\Resource;

use OpenCloud\Tests\Identity\IdentityTestCase;

class TenantTest extends IdentityTestCase
{
    public function test_Methods()
    {
        $this->addMockSubscriber($this->makeResponse('{"tenants":[{"id":1234}]}'));
        $tenants = $this->service->getTenants();
        $tenant = $tenants->current();

        $tenant->setId(321);
        $this->assertEquals(321, $tenant->getId());

        $tenant->setName('foo');
        $this->assertEquals('foo', $tenant->getName());

        $tenant->setDescription('bar');
        $this->assertEquals('bar', $tenant->getDescription());

        $tenant->setEnabled(false);
        $this->assertFalse($tenant->isEnabled());
    }
}