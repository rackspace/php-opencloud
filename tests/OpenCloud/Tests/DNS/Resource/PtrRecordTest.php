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

namespace OpenCloud\Tests\DNS\Resource;

use OpenCloud\Tests\DNS\DnsTestCase;

class PtrRecordTest extends DnsTestCase
{
    private function getServer()
    {
        return $this->getClient()
            ->computeService('cloudServersOpenStack', 'ORD')
            ->server('foo');
    }

    public function test_Has_Correct_Type()
    {
        $this->assertEquals('PTR', $this->record->type);
    }

    public function test_Url()
    {
        $this->assertEquals(
            'https://dns.api.rackspacecloud.com/v1.0/123456/rdns',
            (string) $this->record->getUrl()
        );
    }

    public function test_Create()
    {
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse',
            $this->record->create(array('parent' => $this->getServer()))
        );
    }

    public function test_Update()
    {
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse',
            $this->record->update(array('parent' => $this->getServer()))
        );
    }

    public function test_Delete()
    {
        $this->record->setDeviceParent($this->getServer());
        $this->record->data = 12345;

        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse',
            $this->record->delete()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_Operation_Fails_Without_Parent_Device()
    {
        $this->record->create();
    }

    public function test_Load_Balancer_Can_Have_PtrRecords()
    {
        $lb = $this->getClient()
            ->loadBalancerService('cloudLoadBalancers', 'ORD')
            ->loadBalancer('foo');

        $this->record->setDeviceParent($lb);

        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\HasPtrRecordsInterface',
            $this->record->getDeviceParent()
        );
    }
}
