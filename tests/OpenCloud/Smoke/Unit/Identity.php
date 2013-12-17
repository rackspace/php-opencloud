<?php

namespace OpenCloud\Smoke\Unit;

class Identity extends AbstractUnit implements UnitInterface
{
    public function setupService()
    {
        return $this->getClient()->identityService();
    }

    public function main()
    {
        $this->executeUsers();
        $this->executeRoles();
        $this->executeTokens();
        $this->executeTenants();
    }

    public function executeUsers()
    {
        // list users

        // get user

        // create user

        // update user

        // delete user

    }

    public function teardown()
    {

    }
} 