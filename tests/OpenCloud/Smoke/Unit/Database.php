<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Utils;

class Database extends AbstractUnit implements UnitInterface
{
    const INSTANCE_NAME = 'FooInstance';
    const DATABASE_NAME = 'FooDb';
    const USER_NAME = 'FooUser';

    public function setupService()
    {
        return $this->getConnection()->databaseService('cloudDatabases', Utils::getRegion(), 'publicURL');
    }

    public function main()
    {
        $this->step('Get Flavors');
        
        $flavors = $this->getService()->flavorList();
        //$flavors->sort();
        foreach ($flavors as $flavor) {
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
        $databases = $this->getService()->instanceList();
        foreach ($databases as $database) {
            $this->stepInfo(
                'Database Instance: %s (id %s)',
                $database->name,
                $database->id
            );
        }
    }

    public function teardown()
    {
        $this->step('Teardown');
        
        $instances = $this->getService()->instanceList();
        foreach ($instances as $instance) {
            // Users
            $users = $instance->userList();
            foreach ($users as $user) {
                if ($this->shouldDelete($user->name)) {
                    $this->stepInfo('Deleting user: %s', $user->name);
                    $user->delete();
                }
            }
            
            // Databases
            $databases = $instance->databaseList();
            foreach ($databases as $database) {
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
