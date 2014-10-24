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

namespace OpenCloud\Tests\Volume\Resource;

use OpenCloud\Tests\Volume\VolumeTestCase;

class VolumeTest extends VolumeTestCase
{
    public function test_Create()
    {
        $this->addMockSubscriber($this->makeResponse('{"volume_type":{"id":"1","name":"SATA","extra_specs":{}}}'));
        $type = $this->service->volumeType('type_1');

        $volume = $this->service->volume()->create(array(
            'snapshot_id'         => 1,
            'display_name'        => 2,
            'display_description' => 3,
            'size'                => 4,
            'volume_type'         => $type,
            'availability_zone'   => 6,
            'metadata'            => array('foo' => 'bar')
        ));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Update()
    {
        $this->volume->Update();
    }

    public function testName()
    {
        $this->volume->display_name = 'FOOBAR';
        $this->assertEquals('FOOBAR', $this->volume->Name());
    }

    public function testJsonName()
    {
        $this->assertEquals('volume', $this->volume->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('volumes', $this->volume->ResourceName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUpdateDisallowedProperties()
    {
        $this->volume->rename(array(
          'size' => 314
      ));
    }
}
