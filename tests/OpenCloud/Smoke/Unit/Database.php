<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Utils;

/**
 * Description of Database
 * 
 * @link 
 */
class Database extends AbstractUnit implements UnitInterface
{
    const INSTANCE_NAME = 'FooInstance';
    const DATABASE_NAME = 'FooDb';
    const USER_NAME = 'FooUser';
    
    /**
     * {@inheritDoc}
     */
    public function setupService()
    {
        return $this->getConnection()->dbService('cloudDatabases', Utils::getRegion(), 'publicURL');
    }
    
    /**
     * {@inheritDoc}
     */
    public function main()
    {
        $this->step('Get Flavors');
        
        $flavors = $this->getService()->flavorList();
        $flavors->sort();
        while ($flavor = $flavors->next()) {
            $this->stepInfo('%s - %dM', $flavor->name, $flavor->ram);
        }

        
        $this->step('Creating a Database Instance');
        
        $instance = $this->getService()->instance();
        $instance->name = $this->prepend(self::INSTANCE_NAME);
        $instance->flavor = $this->getService()->flavor(1);
        $instance->volume->size = 1;
        $instance->create();
        $instance->waitFor('ACTIVE', 600, $this->getWaiterCallback());

        
        $this->step('Is the root user enabled?');
        
        if ($instance->isRootEnabled()) {
            $this->stepInfo('Yes');
        } else {
            $this->stepInfo('No');
        }

        $this->step('Creating a new database');
        
        $db = $instance->database();
        $db->create(array(
            'name' => $this->prepend(self::DATABASE_NAME)
        ));
        
        $this->step('Creating a new database user');
        
        $user = $instance->user();
        $user->create(array(
            'name'      => 'SmokeTest',
            'password'  => 'BAR',
            'databases' => array(
                $this->prepend(self::DATABASE_NAME)
            )
        ));

        $this->step('List database instances');
        $databasePages = $this->getService()->instanceList();
        
        while ($databasePage = $databasePages->nextPage()) {
            while($database = $databasePage->next()) {
                $this->stepInfo(
                    'Database Instance: %s (id %s)', 
                    $database->name, 
                    $database->id
                );
            }
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function teardown()
    {
        $this->step('Teardown');
        
        $instances = $this->getService()->instanceList();
        while ($instance = $instances->next()) {
            
            // Users
            $users = $instance->userList();
            while ($user = $users->next()) {
                if ($this->shouldDelete($user->name)) {
                    $this->stepInfo('Deleting user: %s', $user->name);
                    $user->delete();
                }
            }
            
            // Databases
            $databases = $instance->databaseList();
            while ($database = $databases->next()) {
                if ($this->shouldDelete($database->name)) {
                    $this->stepInfo('Deleting database: %s', $database->name);
                    $database->delete();
                }
            }
            
            // Instance            
            if ($this->shouldDelete($instance->name)) {
                $this->stepInfo('Deleting instance: %s', $instance->id);
                $instance->delete();
            } 
        }
    }
}