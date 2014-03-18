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

use OpenCloud\LoadBalancer\Resource\Algorithm;
use OpenCloud\LoadBalancer\Resource\AllowedDomain;
use OpenCloud\Tests\LoadBalancer\LoadBalancerTestCase;

class SubResourceTest extends LoadBalancerTestCase
{
    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Access_Update_Fails()
    {
        $this->loadBalancer->access()->update();
    }

    public function test_Connection_Create_Goes_To_Update()
    {
        $this->loadBalancer->connectionThrottle()->create();
        $this->loadBalancer->errorPage()->create();
        $this->loadBalancer->healthMonitor()->create();
        $this->loadBalancer->SSLTermination()->create();
    }

    public function test_Metadata()
    {
        $metadata = $this->loadBalancer->metadata();
        $metadata->key = 'foo';
        $this->assertEquals('foo', $metadata->name());
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function test_Read_Only_Create_Fails()
    {
        $this->loadBalancer->nodeEvent()->create();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Read_Only_Update_Fails()
    {
        $this->loadBalancer->nodeEvent()->update();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DeleteError
     */
    public function test_Read_Only_Delete_Fails()
    {
        $this->loadBalancer->nodeEvent()->delete();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function test_Virtual_IP_Update_Fails()
    {
        $this->loadBalancer->virtualIp()->update();
    }

    public function test_Algorithm_Cannot_Refresh()
    {
        $resource = new Algorithm($this->service);
        $this->assertFalse($resource->refresh());
    }

    public function test_Allowed_Domain_Cannot_Refresh()
    {
        $resource = new AllowedDomain($this->service);
        $this->assertFalse($resource->refresh());
    }
}
