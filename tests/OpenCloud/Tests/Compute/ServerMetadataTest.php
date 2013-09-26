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

namespace OpenCloud\Tests\Compute;

class ServerMetadataTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $server;
    private $metadata;

    public function __construct()
    {
        $service = $this->getClient()->compute('cloudServersOpenStack', 'DFW', 'publicURL');
        $this->server = $service->server('Identifier');
        $this->metadata = $this->server->metadata();
    }

    /**
     * Tests
     */
    public function test___construct()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\ServerMetadata', $this->metadata);
        $this->assertEquals('bar', $this->metadata->foo);

        // now test individual property
        $met = $this->server->metadata('foobar');
        $met->foobar = 'BAZ';
        $this->assertEquals('BAZ', $met->foobar);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/9bfd203a-0695-xxxx-yyyy-66c4194c967b/metadata', 
            $this->metadata->Url()
        );
        $m2 = $this->server->metadata('property');
        $this->assertEquals(
            'https://dfw.servers.api.rackspacecloud.com/v2/9999/servers/9bfd203a-0695-xxxx-yyyy-66c4194c967b/metadata/property', 
            $m2->url()
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
        $this->metadata->foo = 'bar';
        $this->metadata->create();
        $this->assertEquals('bar', $this->metadata->foo);
    }

    public function testUpdate()
    {
        $this->metadata->foo = 'baz';
        $this->metadata->update();
        $this->assertEquals('baz', $this->metadata->foo);
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
    
    public function testKeyAssignment()
    {
        $metadata = $this->server->metadata('baz');
        $metadata->baz = 'foo';
        $metadata->update();
    }
    
}
