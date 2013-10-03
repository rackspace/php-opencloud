<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Compute\Service as ComputeService;
use OpenCloud\Tests\StubConnection;

// make a real class from the abstract one
class MyPersistentObject extends PersistentObject
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

    public function Refresh($id = NULL, $url = NULL)
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

class NamelessObject extends PersistentObject
{
}

class PersistentObjectTest extends PHPUnit_Framework_TestCase
{

    private $service;
    private $instance;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new ComputeService(
            $conn, 'cloudServersOpenStack', array('DFW'), 'publicURL'
        );
        $this->instance = new MyPersistentObject($this->service);
    }

    /**
     * Tests
     */
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
        $inst = new MyPersistentObject($this->service, FALSE);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\AttributeError
     */
    public function test__set()
    {
        $this->instance->FOOBAR = 'BAZ';
    }

    public function testUrl()
    {
        $hostnames = $this->service->getHostnames();
        $this->instance->id = '12';
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/' .
            'TENANT-ID/instances/12', $hostnames[0] . $this->instance->Url());
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/' .
            'instances/12/foobar?foo=BAZ', $hostnames[0] . $this->instance->Url('foobar', array('foo' => 'BAZ')));
    }

    public function testUrl2()
    {
        $this->instance->id = '12';
        $hostnames = $this->service->getHostnames();
        /* this tests for subresources and query strings */
        $qstr = array('a' => 1, 'b' => 2);
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/instances/12/pogo?a=1&b=2', 
            $hostnames[0] . $this->instance->Url('pogo', $qstr)
        );
    }

    public function testRefresh()
    {
        $this->instance->Refresh('SERVER-ID');
        $this->assertEquals('ACTIVE', $this->instance->status);
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
    
//    public function testWaitForSleep()
//    {
//        $this->instance->id = 11;
//        $this->instance->waitFor('BAZ', 2, array($this, 'waitForCallBack'), 1);
//    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreate()
    {
        $this->instance->Create();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdate()
    {
        $this->instance->Update();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UrlError
     */
    public function testDelete()
    {
        $this->instance->Delete();
    }

    public function testName()
    {
        $this->assertEquals('', $this->instance->Name());
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
        $this->assertInstanceOf('OpenCloud\Compute\Service', $this->instance->Service());
    }

    public function testParent()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Service', $this->instance->Parent());
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
        $hostnames = $this->service->getHostnames();
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/instances', 
            $hostnames[0] . $this->instance->CreateUrl()
        );
    }

    public function testRegion()
    {
        $this->assertEquals(array('DFW'), $this->instance->Region());
    }
    
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ServiceValueError
     */
    public function testGettingServiceFailsIfNotSet()
    {
        $service = new MyPersistentObject;
        $service->getService();
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
        $object = new NamelessObject;
        $object->name();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\IdRequiredError
     */
    public function testActionWhenNoId()
    {
        $this->instance->id = null;
        $this->instance->action(null);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ServerActionError
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
            (object) array(
                "href" => "https://dfw.servers.api.rackspacecloud.com/9999/servers/9bfd203a-0695-xxxx-yyyy-66c4194c967b",
                "rel"  => "bookmark"
            )
        );
        $this->assertFalse($server->findLink());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DocumentError
     */
    public function testJsonNameFailsIfNotSet()
    {
        $server = new NamelessObject;
        $server->jsonName();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UrlError
     */
    public function testResourceNameFailsIfNotSet()
    {
        $server = new NamelessObject;
        $server->resourceName();
    }
    
}
