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

namespace OpenCloud\Tests\DNS;

use PHPUnit_Framework_TestCase;
use OpenCloud\Compute;
use OpenCloud\DNS\Service;
use OpenCloud\Tests\StubConnection;

class DnsTest extends PHPUnit_Framework_TestCase
{

    private $conn;
    private $dns;

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->dns = new Service($this->conn, 'cloudDNS', 'N/A', 'publicURL');
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $thing = new Service($this->conn, 'cloudDNS', 'N/A', 'publicURL');
        $this->assertInstanceOf('OpenCloud\DNS\Service', $thing);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dns.api.rackspacecloud.com/v1.0/TENANT-ID', 
            $this->dns->url()
        );
    }

    public function testDomain()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Domain', $this->dns->domain());
    }

    public function testDomainList()
    {
        $list = $this->dns->domainList();
        $this->assertInstanceOf('OpenCloud\Common\Collection', $list);
        $this->assertGreaterThan(2, strlen($list->Next()->Name()));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\AsyncHttpError
     */
    public function testAsyncRequest()
    {
        $this->dns->AsyncRequest('FOOBAR');
    }

    public function testImport()
    {
        $this->assertInstanceOf(
            'OpenCloud\DNS\AsyncResponse', 
            $this->dns->Import('foo bar oops')
        );
    }

    public function testPtrRecordList()
    {
        $server = new Compute\Server(
            new Compute\Service($this->conn, 'cloudServersOpenStack', 'DFW', 'publicURL')
        );
        $server->id = '42';
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $this->dns->PtrRecordList($server)
        );
    }

    public function testRecord()
    {
        $this->assertInstanceOf('OpenCloud\DNS\PtrRecord', $this->dns->PtrRecord());
    }

    public function testLimits()
    {
        $obj = $this->dns->Limits();
        $this->assertTrue(is_array($obj->rate));
        $obj = $this->dns->Limits('DOMAIN_LIMIT');
        $this->assertEquals(500, $obj->absolute->limits[0]->value);
    }

    public function testLimitTypes()
    {
        $arr = $this->dns->LimitTypes();
        $this->assertTrue(in_array('RATE_LIMIT', $arr));
    }

}
