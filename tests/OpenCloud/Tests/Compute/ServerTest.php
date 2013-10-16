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

class ServerTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;
    private $server;

    public function __construct()
    {
        $this->service = $this->getClient()->compute('cloudServersOpenStack', 'DFW', 'publicURL');
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
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/' .
            '9bfd203a-0695-xxxx-yyyy-66c4194c967b', $this->server->Url());
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/' .
            '9bfd203a-0695-xxxx-yyyy-66c4194c967b/action', $this->server->Url('action'));
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
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals($this->getClient()->getUserAgent(), $this->server->metadata->sdk);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidParameterError
     */
    public function test_Create_Fails_Without_KeyPair_Name()
    {
        $this->server->create(array(
            'keypair' => array('name' => null)
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidParameterError
     */
    public function test_Create_Fails_Without_KeyPair_PublicKey()
    {
        $this->server->create(array(
            'keypair' => array('name' => 'foo')
        ));
    }

    public function test_Create_With_KeyPair()
    {
        $this->server->create(array(
            'keypair' => array(
                'name'      => 'foo',
                'publicKey' => 'bar'
            )
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\RebuildError
     */
    public function testRebuild1()
    {
        $resp = $this->server->rebuild();
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals($this->getClient()->getUserAgent(), $this->server->metadata->sdk);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\RebuildError
     */
    public function testRebuild2()
    {
        $resp = $this->server->Rebuild(array('adminPass' => 'FOOBAR'));
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals($this->getClient()->getUserAgent(), $this->server->metadata->sdk);
    }

    public function testRebuild3()
    {
        $image = $this->service->Image();
        $image->id = '123';
        $resp = $this->server->Rebuild(array(
            'adminPass' => 'FOOBAR',
            'image'     => $image
        ));
        $this->assertNotNull($resp->getStatusCode());
    }

    public function testDelete()
    {
        $resp = $this->server->delete();
        $this->assertNotNull($resp->getStatusCode());
    }

    public function testUpdate()
    {
        $resp = $this->server->Update(array('name' => 'FOO-BAR'));
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals('FOO-BAR', $this->server->name);
    }

    public function testReboot()
    {
        $this->assertEquals(200, $this->server->Reboot()->getStatusCode());
    }
    
    /**
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testCreateImage()
    {
        $resp = $this->server->createImage('EPIC-IMAGE', array('foo' => 'bar'));
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
        $this->assertEquals(200, $this->server->Resize($this->service->Flavor(4))->getStatusCode());
    }

    public function testResizeConfirm()
    {
        $this->assertEquals(200, $this->server->ResizeConfirm()->getStatusCode());
    }

    public function testResizeRevert()
    {
        $this->assertEquals(200, $this->server->ResizeRevert()->getStatusCode());
    }

    public function test_SetPassword()
    {
        $this->assertEquals(200, $this->server->SetPassword('Bad Password')->getStatusCode());
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

    public function testService()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service', $this->server->getService()
        );
    }

    public function testResourceName()
    {
        $server = new Server($this->service);
        $server->id = 'Bad-ID';
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/TENANT-ID/servers/Bad-ID', 
            $server->Url()
        );
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
        $this->assertEquals(200, $resp->getStatusCode());
        $blank = new Server($this->service);
        $blank->unrescue(); // should trigger the exception
    }

    public function testAttachVolume()
    {
        $vol = new Volume($this->service);
        $response = $this->server->AttachVolume($vol);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDetachVolume()
    {
        $vol = new Volume($this->service, 'FOO');
        $response = $this->server->DetachVolume($vol);
        $this->assertEquals(202, $response->getStatusCode());
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
