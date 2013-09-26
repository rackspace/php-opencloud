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

use OpenCloud\Compute;

class DnsTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;

    public function __construct()
    {
        $this->service = $this->getClient()->dns('cloudDNS', 'N/A', 'publicURL');
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Service', $this->service);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dns.api.rackspacecloud.com/v1.0/TENANT-ID', 
            $this->service->url()
        );
    }

    public function testDomain()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Domain', $this->service->domain());
    }

    public function testDomainList()
    {
        $list = $this->service->domainList();
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
            'OpenCloud\DNS\Resource\AsyncResponse', 
            $this->dns->Import('foo bar oops')
        );
    }

    public function testPtrRecordList()
    {
        $server = new Compute\Resource\Server(
            $this->getClient()->compute('cloudServersOpenStack', 'DFW', 'publicURL')
        );
        $server->id = '42';
        $this->assertInstanceOf(
            'OpenCloud\Common\Collection', 
            $this->service->PtrRecordList($server)
        );
    }

    public function testRecord()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\PtrRecord', $this->service->PtrRecord());
    }

    public function testLimits()
    {
        $obj = $this->service->Limits();
        $this->assertTrue(is_array($obj->rate));
        $obj = $this->service->Limits('DOMAIN_LIMIT');
        $this->assertEquals(500, $obj->absolute->limits[0]->value);
    }

    public function testLimitTypes()
    {
        $arr = $this->service->LimitTypes();
        $this->assertTrue(in_array('RATE_LIMIT', $arr));
    }

}
