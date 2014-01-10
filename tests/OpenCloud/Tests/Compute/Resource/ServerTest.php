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

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Compute\Constants\Network;
use OpenCloud\Compute\Resource\Server;
use OpenCloud\Tests\Compute\ComputeTestCase;
use OpenCloud\Volume\Resource\Volume;

class PublicServer extends Server
{
    public function CreateJson($x = 'server')
    {
        return parent::CreateJson($x);
    }
}

class ServerTest extends ComputeTestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\Server', $this->server);
    }

    public function test_Url()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/servers/ef08aa7a-b5e4-4bb8-86df-5ac56230f841',
            (string) $this->server->getUrl()
        );
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/servers/ef08aa7a-b5e4-4bb8-86df-5ac56230f841/action',
            (string) $this->server->getUrl('action')
        );
    }

    public function test_Ip()
    {
        $this->assertEquals('198.101.241.238', $this->server->ip(4));
        $this->assertEquals('2001:4800:780e:0510:d87b:9cbc:ff04:513a', $this->server->ip(6));
    }

    public function test_Create()
    {
        $resp = $this->service->server()->create(array(
            'flavor' => $this->service->flavorList()->first()
        ));
        $this->assertNotNull($resp->getStatusCode());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidParameterError
     */
    public function test_Create_Fails_Without_KeyPair_Name()
    {
        $this->service->server()->create(array(
            'keypair' => array('name' => null)
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidParameterError
     */
    public function test_Create_Fails_Without_KeyPair_PublicKey()
    {
        $this->service->server()->create(array(
            'keypair' => array('name' => 'foo')
        ));
    }

    public function test_Create_With_KeyPair()
    {
        $this->service->server()->create(array(
            'keypair' => array(
                'name'      => 'foo',
                'public_key' => 'bar'
            )
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\RebuildError
     */
    public function test_Rebuild1()
    {
        $resp = $this->server->rebuild();
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals($this->getClient()->getUserAgent(), $this->server->metadata->sdk);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\RebuildError
     */
    public function test_Rebuild2()
    {
        $resp = $this->server->Rebuild(array('adminPass' => 'FOOBAR'));
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals($this->getClient()->getUserAgent(), $this->server->metadata->sdk);
    }

    public function test_Rebuild3()
    {
        $image = $this->service->Image();
        $image->id = '123';
        $resp = $this->server->Rebuild(array(
            'adminPass' => 'FOOBAR',
            'image'     => $image
        ));
        $this->assertNotNull($resp->getStatusCode());
    }

    public function test_Delete()
    {
        $resp = $this->server->delete();
        $this->assertNotNull($resp->getStatusCode());
    }

    public function test_Update()
    {
        $resp = $this->server->Update(array('name' => 'FOO-BAR'));
        $this->assertNotNull($resp->getStatusCode());
        $this->assertEquals('FOO-BAR', $this->server->name);
    }

    public function test_Reboot()
    {
        $this->assertEquals(200, $this->server->reboot()->getStatusCode());
    }
    
    /**
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function test_Create_Image()
    {
        $this->addMockSubscriber(new \Guzzle\Http\Message\Response(404));
        $resp = $this->server->createImage('EPIC-IMAGE', array('foo' => 'bar'));
        $this->assertFalse($resp);
    }
    
    public function test_Create_Fails_Without_Response()
    {
        $this->assertFalse($this->server->createImage('foo'));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ImageError
     */
    public function test_Create_Image_Fails_Without_Name()
    {
        $this->server->createImage(null);
    }

    public function test_Resize()
    {
        $this->assertEquals(200, $this->server->Resize($this->service->Flavor(4))->getStatusCode());
    }

    public function test_Resize_Confirm()
    {
        $this->assertEquals(200, $this->server->ResizeConfirm()->getStatusCode());
    }

    public function test_Resize_Revert()
    {
        $this->assertEquals(200, $this->server->ResizeRevert()->getStatusCode());
    }

    public function test_Set_Password()
    {
        $this->assertEquals(200, $this->server->SetPassword('Bad Password')->getStatusCode());
    }

    public function test_Metadata()
    {
        $server = new Server($this->service);
        // this causes the exception
        $this->assertTrue(is_object($server->Metadata()));
    }

    public function test_Metadata_More()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\ServerMetadata', 
            $this->server->Metadata()
        );
    }

    public function test_Ips()
    {
        $this->assertTrue(is_object($this->server->ips()));
    }

    public function test_Ips_Network()
    {
        $this->assertTrue(is_object($this->server->ips('public')));
    }

    public function test_Service()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service', $this->server->getService()
        );
    }

    public function test_Resource_Name()
    {
        $server = new Server($this->service);
        $server->id = 'Bad-ID';
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/servers/Bad-ID',
            (string) $server->getUrl()
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServerActionError
     */
    public function test_Rescue()
    {
        $this->addMockSubscriber($this->makeResponse('{"adminPass": "m7UKdGiKFpqM"}'));
        $password = $this->server->Rescue();
        $this->assertGreaterThan(5, strlen($password));
        $blank = new Server($this->service);
        $blank->rescue(); // should trigger the exception
    }
    
    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServerActionError
     */
    public function test_Rescue_Fails_Without_Id()
    {
        $blank = new Server($this->service);
        $blank->id = null;
        $blank->rescue(); // should trigger the exception
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServerActionError
     */
    public function test_Unrescue()
    {
        $resp = $this->server->Unrescue();
        $this->assertEquals(200, $resp->getStatusCode());
        $blank = new Server($this->service);
        $blank->unrescue(); // should trigger the exception
    }

    public function test_Attaching_Detaching_Volume()
    {
        $volume = new Volume($this->service);

        $this->assertInstanceOf(self::RESPONSE_CLASS, $this->server->attachVolume($volume));
        $this->assertInstanceOf(self::RESPONSE_CLASS, $this->server->detachVolume($volume));
    }

    public function test_Volume_Attachment()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Resource\VolumeAttachment', 
            $this->server->volumeAttachment()
        );
    }

    public function test_Volume_Attachment_List()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->server->volumeAttachmentList()
        );
    }

    public function test_Create_Personality()
    {
        $new = new PublicServer($this->service);
        $new->addFile('/tmp/hello.txt', 'Hello, world!');
        $obj = $new->CreateJson();
        
        $this->assertTrue(is_array($obj->server->personality));
        $this->assertEquals(
            '/tmp/hello.txt', $obj->server->personality[0]->path);
    }
    
    public function test_Image_Schedule()
    {
        // Get current backups
        $this->server->imageSchedule();
        
        $this->server->imageSchedule(true);
        
        $this->server->imageSchedule(0);
    }

    public function test_Create_With_Networks()
    {
        $this->service->server()->create(array(
            'name'     => 'personality test 1',
            'image'    => $this->service->imageList()->first(),
            'flavor'   => $this->service->flavorList()->first(),
            'networks' => array(
                $this->service->network(Network::RAX_PUBLIC),
                $this->service->network()
            )
        ));
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidParameterError
     */
    public function test_Create_Fails_Without_Correct_Networks()
    {
        $this->service->server()->create(array(
            'name'     => 'personality test 1',
            'image'    => $this->service->imageList()->first(),
            'flavor'   => $this->service->flavorList()->first(),
            'networks' => array(
                1234
            )
        ));
    }
    
}
