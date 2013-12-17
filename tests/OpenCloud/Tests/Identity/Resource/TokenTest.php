<?php

namespace OpenCloud\Tests\Identity\Resource;

use OpenCloud\Tests\Identity\IdentityTestCase;

class TokenTest 
{
    public function test_Methods()
    {
        $token = $this->service->

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