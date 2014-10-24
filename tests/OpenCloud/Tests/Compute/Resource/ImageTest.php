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

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Compute\Resource\Image;
use OpenCloud\Tests\Compute\ComputeTestCase;

class ImageTest extends ComputeTestCase
{
    public function test_good_image()
    {
        $image = new Image($this->service);
        $this->assertEquals(null, $image->status);
        $this->assertEquals('OpenCloud\Common\Metadata', get_class($image->getMetadata()));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreate()
    {
        $image = $this->service->image();
        $image->create();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $image = $this->service->image();
        $image->update();
    }
}
