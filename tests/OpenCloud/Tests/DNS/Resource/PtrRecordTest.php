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

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\DNS\Resource;

use OpenCloud\DNS\Resource\Object;
use OpenCloud\Tests\DNS\DnsTestCase;

class CustomRecord extends Object
{
    public $name = 'fooBar';
}

class PtrRecordTest extends DnsTestCase
{
    /**
     * Tests
     */
    public function test__construct()
    {
        $this->record = $this->service->ptrRecord();
        $this->assertEquals('PTR', $this->record->type);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\RecordTypeError
     */
    public function test__construct2()
    {
        $this->service->ptrRecord(array('type' => 'A'));
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dns.api.rackspacecloud.com/v1.0/123456/rdns',
            (string) $this->record->getUrl()
        );
    }

    public function testCreate()
    {
        $server = $this->getClient()->computeService(null, 'ORD')->server(array('id' => 'foo'));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->create(array('server' => $server))
        );
    }

    public function testUpdate()
    {
        $server = $this->getClient()->computeService(null, 'ORD')->server(array('id' => 'foo'));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->update(array('server' => $server))
        );
    }

    public function testDelete()
    {
        $server = $this->getClient()->computeService(null, 'ORD')->server(array('id' => 'foo'));
        $this->record->server = $server;
        $this->record->data   = 12345;
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->record->delete()
        );
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFailsWithoutKeys()
    {
        $object = new CustomRecord($this->service);
        $object->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFailsWithoutKeys()
    {
        $object = new CustomRecord($this->service);
        $object->update();
    }

}
