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

namespace OpenCloud\Tests\LoadBalancer\Resource;

use OpenCloud\Tests\LoadBalancer\LoadBalancerTestCase;

class SubResourceTest extends LoadBalancerTestCase
{

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testAccessUpdateFails()
    {
        $this->loadBalancer->access()->update();
    }

    public function testConnectionCreateGoesToUpdate()
    {
        $this->loadBalancer->connectionLogging()->create();
        $this->loadBalancer->connectionThrottle()->create();
        $this->loadBalancer->contentCaching()->create();
        $this->loadBalancer->errorPage()->create();
        $this->loadBalancer->healthMonitor()->create();
        $this->loadBalancer->SSLTermination()->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testConnectionDeleteFails()
    {
        $this->loadBalancer->connectionLogging()->delete();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testContentCachingDeleteFails()
    {
        $this->loadBalancer->contentCaching()->delete();
    }

    public function testMetadata()
    {
        $metadata = $this->loadBalancer->metadata();
        $metadata->key = 'foo';
        $this->assertEquals('foo', $metadata->name());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testReadOnlyCreateFails()
    {
        $this->loadBalancer->nodeEvent()->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testReadOnlyUpdateFails()
    {
        $this->loadBalancer->nodeEvent()->update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testReadOnlyDeleteFails()
    {
        $this->loadBalancer->nodeEvent()->delete();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testVirtualIPUpdateFails()
    {
        $this->loadBalancer->virtualIp()->update();
    }

    public function test_Name()
    {
        $this->assertEquals('ContentCaching-2000', $this->loadBalancer->contentCaching()->name());
    }
}
