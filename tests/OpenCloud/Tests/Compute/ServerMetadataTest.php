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

use PHPUnit_Framework_TestCase;
use OpenCloud\Compute\Resource\Server;
use OpenCloud\Compute\Resource\ServerMetadata;
use OpenCloud\Compute\Service;
use OpenCloud\Tests\StubConnection;

class ServerMetadataTest extends PHPUnit_Framework_TestCase
{

    private $server;
    private $metadata;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $compute = new Service(
            $conn, 'cloudServersOpenStack', 'DFW', 'publicURL'
        );
        $this->server = new Server($compute, 'Identifier');
        $this->metadata = new ServerMetadata($this->server);
    }

    /**
     * Tests
     */
    public function test___construct()
    {
        $this->assertInstanceOf('OpenCloud\Compute\Resource\ServerMetadata', $this->metadata);
        // test whole group
        $metadata = $this->server->metadata();
        $this->assertEquals('bar', $metadata->foo);
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
        $m2 = new ServerMetadata($this->server, 'property');
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
        $m2 = new ServerMetadata($this->server, 'property');
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
        $metadata = new ServerMetadata($server);
        $metadata->url();
    }
    
    public function testKeyAssignment()
    {
        $metadata = new ServerMetadata($this->server, 'baz');
        $metadata->baz = 'foo';
        $metadata->update();
    }
    
}
