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
 * @version   1.0.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Resource\NovaResource;

class MyPersistentObject extends NovaResource
{
    public $status;
    public $updated;
    public $hostId;
    public $addresses;
    public $links;
    public $image;
    public $hostname;
    public $flavor;
    public $id;
    public $user_id;
    public $name;
    public $created;
    public $tenant_id;
    public $accessIPv4;
    public $accessIPv6;
    public $volume;
    public $progress;
    public $adminPass;
    public $metadata;

    protected static $json_name = 'instance';
    protected static $json_collection_name = 'instanceCollection';
    protected static $url_resource = 'instances';

    public function Refresh($id = null, $url = null)
    {
        return parent::Refresh($id, $url);
    }

    public function NoCreate()
    {
        return parent::NoCreate();
    }

    public function NoUpdate()
    {
        return parent::NoUpdate();
    }

    public function NoDelete()
    {
        return parent::NoDelete();
    }

    public function Action($object)
    {
        return parent::Action($object);
    }

    public function CreateUrl()
    {
        return parent::CreateUrl();
    }
}

class NamelessObject extends NovaResource
{
}

class PersistentObjectTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $service;
    private $instance;

    public function setupObjects()
    {
        $this->service = $this->getClient()->computeService();
        $this->instance = new MyPersistentObject($this->service);
    }

    public function test__construct()
    {
        $inst = new MyPersistentObject($this->service);
        $this->assertInstanceOf('OpenCloud\Tests\Common\MyPersistentObject', $inst);

        $inst = new MyPersistentObject($this->service, array('id' => '42'));
        $this->assertInstanceOf('OpenCloud\Tests\Common\MyPersistentObject', $inst);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test__construct2()
    {
        $inst = new MyPersistentObject($this->service, false);
    }

    public function testUrl()
    {
        $this->instance->id = '12';
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/instances/12',
            (string)$this->instance->getUrl()
        );
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/instances/12/foobar?foo=BAZ',
            (string)$this->instance->getUrl('foobar', array('foo' => 'BAZ'))
        );
    }

    public function testUrl2()
    {
        $this->instance->id = '12';
        /* this tests for subresources and query strings */
        $qstr = array('a' => 1, 'b' => 2);
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/instances/12/pogo?a=1&b=2',
            (string)$this->instance->getUrl('pogo', $qstr)
        );
    }

    /**
     * @mockFile Server
     * @mockPath Compute
     */
    public function testRefresh()
    {
        $this->instance->refresh('ef08aa7a-b5e4-4bb8-86df-5ac56230f841');
        $this->assertObjectHasAttribute('status', $this->instance);
    }

    /**
     * @covers OpenCloud\Common\PersistentObject::waitFor()
     */
    public function testWaitFor()
    {
        $this->instance->id = '11';
        $this->instance->WaitFor('FOOBAR', -1, array($this, 'WaitForCallBack'));
        $this->assertEquals('FOOBAR', $this->instance->status);
    }

    // this is called by the WaitFor function, above
    public function WaitForCallBack($server)
    {
        $server->status = 'FOOBAR';
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreate()
    {
        $this->instance->create();
    }

    public function testName()
    {
        $this->instance->name = '';
        $this->assertEquals('', $this->instance->name());
    }

    public function testStatus()
    {
        $this->assertEquals('N/A', $this->instance->Status());
    }

    public function testId()
    {
        $this->assertNull($this->instance->Id());
    }

    public function testJsonName()
    {
        $this->assertEquals('instance', MyPersistentObject::JsonName());
    }

    public function testResourceName()
    {
        $this->assertEquals('instances', MyPersistentObject::ResourceName());
    }

    public function testJsonCollectionName()
    {
        $this->assertEquals(
            'instanceCollection',
            MyPersistentObject::JsonCollectionName()
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testNoCreate()
    {
        $this->instance->NoCreate();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testNoUpdate()
    {
        $this->instance->NoUpdate();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DeleteError
     */
    public function testNoDelete()
    {
        $this->instance->NoDelete();
    }

    public function testService()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Service', $this->instance->getService());
    }

    public function testParent()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Service', $this->instance->getParent());
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UnsupportedExtensionError
     */
    public function testCheckExtension()
    {
        // this should work
        $this->assertTrue($this->instance->CheckExtension('os-rescue'));
        // this causes the exception
        $this->instance->CheckExtension('foobar');
    }

    public function testAction()
    {
        $obj = new \stdClass;
        $this->instance->id = 'foo';
        $this->instance->Action($obj);
    }

    public function testCreateUrl()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/instances',
            (string)$this->instance->CreateUrl()
        );
    }

    public function testRegion()
    {
        $this->assertEquals('DFW', $this->instance->Region());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\IdRequiredError
     */
    public function testRefreshFailsWithoutId()
    {
        $this->instance->id = null;
        $this->instance->refresh();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\NameError
     */
    public function testGetNameFailsWhenNoName()
    {
        $object = new NamelessObject($this->service);
        $object->name();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testActionWhenNoId()
    {
        $this->instance->id = null;
        $this->instance->action(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testActionFailsWhenNotValidObject()
    {
        $this->instance->id = 123;
        $this->instance->action(array());
    }

    public function testGettingSelfLinkFailsIfNotSet()
    {
        $server = $this->instance;
        $server->links = array(
            (object)array(
                "href" => "https://dfw.servers.api.rackspacecloud.com/9999/servers/9bfd203a-0695-xxxx-yyyy-66c4194c967b",
                "rel"  => "bookmark"
            )
        );
        $this->assertFalse($server->findLink());
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DocumentError
     */
    public function test_Json_Name_Fails_If_Not_Set()
    {
        $server = new NamelessObject($this->service);
        $server->jsonName();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UrlError
     */
    public function testResourceNameFailsIfNotSet()
    {
        $server = new NamelessObject($this->service);
        $server->resourceName();
    }
}
