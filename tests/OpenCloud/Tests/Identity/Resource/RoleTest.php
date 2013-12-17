<?php

namespace OpenCloud\Tests\Identity\Resource;

use OpenCloud\Tests\Identity\IdentityTestCase;

class RoleTest extends IdentityTestCase
{
    public function test_Methods()
    {
        $role = $this->service->getRole(12345);

        $role->setName('foo');
        $this->assertEquals('foo', $role->getName());

        $role->setDescription('bar');
        $this->assertEquals('bar', $role->getDescription());
    }
} 