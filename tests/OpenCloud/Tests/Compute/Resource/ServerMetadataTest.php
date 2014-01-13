<?php
/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Compute\Resource;

use OpenCloud\Tests\Compute\ComputeTestCase;

class ServerMetadataTest extends ComputeTestCase
{

    private $metadata;

    public function setupObjects()
    {
        parent::setupObjects();

        $response = new \Guzzle\Http\Message\Response(200, array('content-type' => 'application/json'), '{"metadata": {"foo": "bar"}}');
        $this->addMockSubscriber($response);
        $this->metadata = $this->server->metadata('foo');
    }

    public function test_Class()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\ServerMetadata', $this->metadata);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/123456/servers/ef08aa7a-b5e4-4bb8-86df-5ac56230f841/metadata/foo',
            (string) $this->metadata->getUrl()
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\MetadataKeyError
     */
    public function test___set()
    {
        $this->metadata->property = 'value';
        $this->assertEquals('value', $this->metadata->property);
        $m2 = $this->server->metadata('property');
        $m2->foo = 'bar'; // should cause exception
        $this->assertNull($m2->foo);
    }

    public function testCreate()
    {
        $metadata = $this->metadata;
        $metadata->create();
        $this->assertEquals('bar', $metadata->foo);
    }

    public function testUpdate()
    {
        $metadata = $this->metadata;
        $metadata->update();
        $this->assertEquals('bar', $metadata->foo);
    }

    public function testDelete()
    {
        $this->metadata->delete();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\ServerUrlError
     */
    public function testUrlFails()
    {
        $server = $this->server;
        $server->id = null;
        $server->metadata()->url();
    }
        
}
