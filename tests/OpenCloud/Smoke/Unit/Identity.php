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
        //$this->executeUsers();
        //$this->executeRoles();
        $this->executeTokens();
        $this->executeTenants();
    }

    public function executeUsers()
    {
        $step1 = $this->step('List users');
        $users = $this->service->getUsers();
        foreach ($users as $_user) {
            $this->stepInfo('User: %s [%s]', $_user->getUsername(), $_user->getId());
        }

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
            'email'    => sprintf('foo_%s@example.com', time()),
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
            $this->stepInfo(sprintf('User: %s [%s]', $_user->getUserName(), $_user->getId()));
        }

        // reset API key
        $this->step('(Re)set API key');
        $this->stepInfo($user->resetApiKey());

        // get API key
        $this->step('Get API key');
        $this->stepInfo($user->getApiKey());

        // list roles
        $step = $this->step('List user roles');
        $roles = $user->getRoles();
        foreach ($roles as $role) {
            $this->stepInfo($role->getName());
        }

        // delete user
        $this->step('Delete user');
        $user->delete();
    }

    public function executeRoles()
    {
        // list roles
        $this->step('List roles');
        $roles = $this->service->getRoles();
        foreach ($roles as $role) {
            $this->stepInfo('Role: %s [%s]', $role->getName(), $role->getId());
            $roleId = $role->getId();
        }

        // DOES NOT WORK
        // get role
        $this->step('Get role');
        //$role = $this->service->getRole($roleId);
        //$this->stepInfo('Role: %s, %s, %s', $roleId, $role->getName(), $role->getDescription());
    }

    public function executeTokens()
    {
        // revoke
        $this->step('Revoke token');
        $this->service->revokeToken($this->getConnection()->getToken());

        $this->getConnection()->authenticate();
    }

    public function executeTenants()
    {
        // list tenants
        $this->step('List tenants');
        $tenants = $this->service->getTenants();
        foreach ($tenants as $tenant) {
            $this->stepInfo('Tenant: %s [%s]', $tenant->getName(), $tenant->getId());
        }
    }

    public function teardown()
    {
    }
} 