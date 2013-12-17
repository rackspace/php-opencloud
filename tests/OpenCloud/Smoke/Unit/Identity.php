<?php

namespace OpenCloud\Smoke\Unit;

class Identity extends AbstractUnit implements UnitInterface
{
    public function setupService()
    {
        return $this->getConnection()->identityService();
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
        // get user
        $this->step('Get user');
        $user = $this->service->getUser('jamiehannaford1', 'name');
        $this->stepInfo('Username: %s', $user->getUsername());
        $this->stepInfo('ID: %s', $user->getId());
        $this->stepInfo('Email: %s', $user->getEmail());
        $this->stepInfo('Default region: %s', $user->getDefaultRegion());

        // create user
        $this->step('Create user');
        $user = $this->service->createUser(array(
            'username' => 'SMOKETEST_user_' . time(),
            'email'    => 'jamie.hannaford@rackspace.com',
            'enabled'  => true
        ));

        // update user
        $this->step('Update user');
        $user->update(array(
            'enabled' => false
        ));

        // list users
        $step1 = $this->step('List users');
        $users = $this->service->getUsers();
        foreach ($users as $_user) {
            $step1->stepInfo(sprintf('User: %s [%s]', $_user->getUserName(), $_user->getId()));
        }

        // get API key
        $this->step('Get API key');
        $this->stepInfo($user->getApiKey());

        // reset API key
        $this->step('Reset API key');
        $this->stepInfo($user->resetApiKey());

        // list roles
        $step = $this->step('List user roles');
        $roles = $user->getRoles();
        foreach ($roles as $role) {
            $step->stepInfo($role->getName());
        }

        // delete user
        $this->step('Delete user');
        $user->delete();
    }

    public function executeRoles()
    {
        // list roles

        // get role
    }

    public function executeTokens()
    {
        // generate

        // revoke
    }

    public function executeTenants()
    {
        // list tenants
    }

    public function teardown()
    {

    }
} 