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

namespace OpenCloud\Tests\Volume;

class ServiceTest extends VolumeTestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            'OpenCloud\Volume\Service',
            $this->service
        );
    }

    public function testVolume()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\Volume', $this->service->Volume());
    }

    public function testVolumeList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->service->volumeList());
    }

    public function testVolumeType()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\VolumeType', $this->service->VolumeType());
    }

    public function testSnapshot()
    {
        $this->assertInstanceOf('OpenCloud\Volume\Resource\Snapshot', $this->service->Snapshot());
    }
}
