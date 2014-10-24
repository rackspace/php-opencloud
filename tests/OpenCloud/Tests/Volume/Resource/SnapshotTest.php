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

class SnapshotTest extends VolumeTestCase
{
    private $snapshot;

    public function setupObjects()
    {
        parent::setupObjects();

        $this->snapshot = $this->service->snapshot();
    }

    public function test_Create()
    {
        $this->snapshot->create(array());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->snapshot->update();
    }

    public function testName()
    {
        $this->snapshot->display_name = 'FOOBAR';
        $this->assertEquals('FOOBAR', $this->snapshot->Name());
    }

    public function testJsonName()
    {
        $this->assertEquals('snapshot', $this->snapshot->JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('snapshots', $this->snapshot->ResourceName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUpdateDisallowedProperties()
    {
        $this->volume->rename(array(
          'volume_id' => 'abcd-ef12'
      ));
    }
}
