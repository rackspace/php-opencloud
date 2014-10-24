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

namespace OpenCloud\Tests\Database\Resource;

use OpenCloud\Tests\Database\DatabaseTestCase;

class ConfigurationTest extends DatabaseTestCase
{
    public function test_Class()
    {
        $this->assertInstanceOf('OpenCloud\Database\Resource\Configuration', $this->configuration);
    }

    public function testUpdateJson()
    {
        $replacementValues = array(
            'description' => 'Updated description',
            'name' => 'Updated name',
            'values' => array(
                'collation_server' => 'utf8_unicode_ci',
                'connect_timeout' => 150
            )
        );
        
        $method = new \ReflectionMethod('OpenCloud\Database\Resource\Configuration', 'updateJson');
        $method->setAccessible(true);
        
        $expected = (object) array(
            'configuration' => $replacementValues
        );
        $this->assertEquals($expected, $method->invoke($this->configuration, $replacementValues));
    }

    public function testInstanceList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->configuration->instanceList());
    }
}
