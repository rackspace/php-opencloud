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

namespace OpenCloud\Tests\CDN\Resource;

use OpenCloud\CDN\Resource\Service;
use OpenCloud\Tests\CDN\CDNTestCase;

class ServiceTest extends CDNTestCase
{
    public function testPurgeSpecificAsset()
    {
        $this->addMockSubscriber($this->makeResponse(null, 202));

        $actualResponse = $this->serviceResource->purgeAssets('/images/foo.png');
        $this->assertEquals(202, $actualResponse->getStatusCode());
    }

    public function testPurgeAllAssets()
    {
        $this->addMockSubscriber($this->makeResponse(null, 202));

        $actualResponse = $this->serviceResource->purgeAssets();
        $this->assertEquals(202, $actualResponse->getStatusCode());
    }
}
