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

namespace OpenCloud\Tests\Autoscale\Resource;

use OpenCloud\Autoscale\Resource\GroupConfiguration;
use OpenCloud\Tests\Autoscale\AutoscaleTestCase;

class GroupConfigurationTest extends AutoscaleTestCase
{
    public function test_Parent_Factory()
    {
        $config = $this->group->getGroupConfig();
        $this->assertInstanceOf(self::CONFIG_CLASS, $config);
        $this->assertInstanceOf(self::GROUP_CLASS, $config->getParent());
    }

    public function test_Manual_Instantiation()
    {
        $config = new GroupConfiguration($this->service);
        $config->setParent($this->service->group());

        $this->assertInstanceOf(self::CONFIG_CLASS, $config);
        $this->assertInstanceOf(self::GROUP_CLASS, $config->getParent());
    }

    /**
     * @mockFile Group_Config
     */
    public function test_Config()
    {
        $this->group->setGroupConfiguration(null);

        $config = $this->group->getGroupConfig();

        $this->assertInstanceOf(
            'OpenCloud\Autoscale\Resource\GroupConfiguration',
            $config
        );

        $this->assertEquals(60, $config->cooldown);
    }

    /**
     * @mockFile Group_Launch_Config
     */
    public function test_Launch_Config()
    {
        $config = $this->group->getLaunchConfig();

        $this->assertEquals('launch_server', $config->getType());

        $server = $config->getArgs()->server;
        $this->assertEquals('0d589460-f177-4b0f-81c1-8ab8903ac7d8', $server->imageRef);
        $this->assertEquals('VGhpcyBpcyBhIHRlc3QgZmlsZS4=', $server->personality[0]->contents);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Group_Config_Create_Fails()
    {
        $this->group->getGroupConfig()->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function test_Group_Config_Delete_Fails()
    {
        $this->group->getGroupConfig()->delete();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Launch_Config_Create_Fails()
    {
        $this->group->getLaunchConfig()->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function test_Launch_Config_Delete_Fails()
    {
        $this->group->getLaunchConfig()->delete();
    }
}
