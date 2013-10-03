<?php
/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Compute;

use PHPUnit_Framework_TestCase;
use OpenCloud\Tests\StubConnection;
use OpenCloud\Compute\Service;
use OpenCloud\Compute\Resource\Server;
use OpenCloud\Volume\Resource\Volume;

class PublicServer extends Server
{
    public function CreateJson($x = 'server')
    {
        return parent::CreateJson($x);
    }
}

class ServerTest extends PHPUnit_Framework_TestCase
{

    private $service;
    private $server;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->service = new Service(
            $conn, 'cloudServersOpenStack', array('DFW'), 'publicURL'
        );
        $this->server = new Server($this->service, 'SERVER-ID');
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Server', $this->server);
    }

    public function testUrl()
    {
        $url = $this->server->Url();
        $this->assertEquals($url, 'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/' .
                '9bfd203a-0695-xxxx-yyyy-66c4194c967b');
        
        $url = $this->server->Url('action');
        $this->assertEquals($url, 'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/' .
            '9bfd203a-0695-xxxx-yyyy-66c4194c967b/action');
    }

    public function test_ip()
    {
        $this->assertEquals('500.6.73.19', $this->server->ip(4));
        $this->assertEquals(
            '2001:4800:780e:0510:199e:7e1e:xxxx:yyyy', $this->server->ip(6));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\InvalidIpTypeError
     */
    public function test_ip_bad()
    {
        $this->assertEquals('FOO', $this->server->ip(5));
    }

    public function testCreate()
    {
        $resp = $this->server->create();
        $this->assertNotNull($resp->HttpStatus());
        $this->assertEquals(RAXSDK_USER_AGENT, $this->server->metadata->sdk);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\RebuildError
     */
    public function testRebuild1()
    {
        $resp = $this->server->Rebuild();
        $this->assertNotNull($resp->HttpStatus());
        $this->assertEquals(RAXSDK_USER_AGENT, $this->server->metadata->sdk);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\RebuildError
     */
    public function testRebuild2()
    {
        $resp = $this->server->Rebuild(array('adminPass' => 'FOOBAR'));
        $this->assertNotNull($resp->HttpStatus());
        $this->assertEquals(RAXSDK_USER_AGENT, $this->server->metadata->sdk);
    }

    public function testRebuild3()
    {
        $image = $this->service->Image();
        $image->id = '123';
        $resp = $this->server->Rebuild(array(
            'adminPass' => 'FOOBAR',
            'image'     => $image
        ));
        $this->assertNotNull($resp->HttpStatus());
    }

    public function testDelete()
    {
        $resp = $this->server->Delete();
        $this->assertNotNull($resp->HttpStatus());
    }

    public function testUpdate()
    {
        $resp = $this->server->Update(array('name' => 'FOO-BAR'));
        $this->assertNotNull($resp->HttpStatus());
        $this->assertEquals('FOO-BAR', $this->server->name);
    }

    public function testReboot()
    {
        $this->assertEquals(200, $this->server->Reboot()->HttpStatus());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InstanceNotFound
     */
    public function testCreateImage()
    {
        $resp = $this->server->createImage('EPIC-IMAGE', array('foo' => 'bar'));
        printf("res\n");
        printf("resp $resp \n");
        $this->assertFalse($resp);
    }
    
    public function testCreateFailsWithoutResponse()
    {
        $this->assertFalse($this->server->createImage('foo'));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ImageError
     */
    public function testCreateImageFailsWithoutName()
    {
        $this->server->createImage(null);
    }

    public function testResize()
    {
        $this->assertEquals(200, $this->server->Resize($this->service->Flavor(4))->HttpStatus());
    }

    public function testResizeConfirm()
    {
        $this->assertEquals(200, $this->server->ResizeConfirm()->HttpStatus());
    }

    public function testResizeRevert()
    {
        $this->assertEquals(200, $this->server->ResizeRevert()->HttpStatus());
    }

    public function test_SetPassword()
    {
        $this->assertEquals(200, $this->server->SetPassword('Bad Password')->HttpStatus());
    }

    public function testMetadata()
    {
        $server = new Server($this->service);
        // this causes the exception
        $this->assertTrue(is_object($server->Metadata()));
    }

    public function testMetadataMore()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\ServerMetadata', 
            $this->server->Metadata()
        );
    }

    public function test_ips()
    {
        $this->assertTrue(is_object($this->server->ips()));
    }

    public function test_ips_network()
    {
        $this->assertTrue(is_object($this->server->ips('public')));
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\AttributeError
     */
    public function test__set()
    {
        $prop = 'rax-bandwidth:foobar';
        $this->server->$prop = 'BAZ';
        $this->assertEquals('BAZ', $this->server->$prop);
        $this->server->foo = 'foobar'; // causes exception
    }

    public function testService()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service', $this->server->Service()
        );
    }

    public function testResourceName()
    {
        $server = new Server($this->service);
        $server->id = 'Bad-ID';
        $url = $server->Url();
        $hostnames = $this->service->getHostnames();
        $this->assertEquals('https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/servers/Bad-ID',
                $hostnames[0] . $url);
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServerActionError
     */
    public function testRescue()
    {
        $password = $this->server->Rescue();
        $this->assertGreaterThan(5, strlen($password));
        $blank = new Server($this->service);
        $blank->rescue(); // should trigger the exception
    }
    
    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServerActionError
     */
    public function testRescueFailsWithoutId()
    {
        $blank = new Server($this->service);
        $blank->id = null;
        $blank->rescue(); // should trigger the exception
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServerActionError
     */
    public function testUnrescue()
    {
        $resp = $this->server->Unrescue();
        $this->assertEquals(200, $resp->HttpStatus());
        $blank = new Server($this->service);
        $blank->unrescue(); // should trigger the exception
    }

    public function testAttachVolume()
    {
        $vol = new Volume($this->service);
        $response = $this->server->AttachVolume($vol);
        $this->assertEquals(200, $response->HttpStatus());
    }

    public function testDetachVolume()
    {
        $vol = new Volume($this->service, 'FOO');
        $response = $this->server->DetachVolume($vol);
        $this->assertEquals(202, $response->HttpStatus());
    }

    public function testVolumeAttachment()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\VolumeAttachment', 
            $this->server->VolumeAttachment()
        );
    }

    public function testVolumeAttachmentList()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $this->server->VolumeAttachmentList()
        );
    }

    public function testCreate_personality()
    {
        $new = new PublicServer($this->service);
        $new->addFile('/tmp/hello.txt', 'Hello, world!');
        $obj = $new->CreateJson();
        
        $this->assertTrue(is_array($obj->server->personality));
        $this->assertEquals(
            '/tmp/hello.txt', $obj->server->personality[0]->path);
    }
    
    public function testImageSchedule()
    {
        // Get current backups
        $this->server->imageSchedule();
        
        $this->server->imageSchedule(true);
        
        $this->server->imageSchedule(0);
    }

    public function testCreateWithNetworks()
    {
        $flavorList = $this->service->flavorList();
        $imageList  = $this->service->imageList();

        $server = $this->service->server();
        $server->create(array(
            'name'     => 'personality test 1',
            'image'    => $imageList->first(),
            'flavor'   => $flavorList->first(),
            'networks' => array(
                $this->service->network(RAX_PUBLIC),
                $this->service->network()
            )
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidParameterError
     */
    public function testCreateFailsWithoutCorrectNetworks()
    {
        $flavorList = $this->service->flavorList();
        $imageList  = $this->service->imageList();

        $server = $this->service->server();
        $server->create(array(
            'name'     => 'personality test 1',
            'image'    => $imageList->first(),
            'flavor'   => $flavorList->first(),
            'networks' => array(
                1234
            )
        ));
    }
    
}
