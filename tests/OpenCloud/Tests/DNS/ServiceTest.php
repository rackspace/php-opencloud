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


namespace OpenCloud\Tests\DNS;

use OpenCloud\Compute;

class ServiceTest extends DnsTestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Service', $this->service);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://dns.api.rackspacecloud.com/v1.0/123456',
            (string)$this->service->getUrl()
        );
    }

    public function testDomain()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Domain', $this->service->domain());
    }

    public function testDomainByName()
    {
        $this->addMockSubscriber($this->makeResponse('{"domains":[{"name":"region2.example.net","id":2725352,"updated":"2011-06-23T20:21:06.000+0000","created":"2011-06-23T19:24:27.000+0000"}],"totalEntries":114}', 200));
        $domain = $this->service->domainByName("region2.example.net");

        $this->assertInstanceOf('OpenCloud\DNS\Resource\Domain', $domain);
        $this->assertEquals("region2.example.net", $domain->getName());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DomainNotFoundException
     */
    public function testDomainByNameWhenDomainNotFound()
    {
        $this->addMockSubscriber($this->makeResponse('{"domains":[],"totalEntries":114}', 200));
        $domain = $this->service->domainByName("region2.example.net");
    }

    /**
     * @mockFile Domain_List
     */
    public function testDomainList()
    {
        $list = $this->service->domainList();
        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);
        $this->assertGreaterThan(2, strlen($list->first()->Name()));
    }

    /**
     * @expectedException Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testAsyncRequest()
    {
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->service->AsyncRequest('FOOBAR');
    }

    public function testImport()
    {
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\AsyncResponse',
            $this->service->Import('foo bar oops')
        );
    }

    public function testPtrRecordList()
    {
        $server = new Compute\Resource\Server(
            $this->getClient()->computeService('cloudServersOpenStack', 'DFW', 'publicURL')
        );
        $server->id = '42';
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->service->PtrRecordList($server)
        );
    }

    public function testRecord()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\PtrRecord', $this->service->PtrRecord());
    }

    public function testLimitTypes()
    {
        $this->addMockSubscriber($this->makeResponse('{"limitTypes": [ "RATE_LIMIT", "DOMAIN_LIMIT", "DOMAIN_RECORD_LIMIT" ]}'));
        $arr = $this->service->LimitTypes();

        $this->assertTrue(in_array('RATE_LIMIT', $arr));
    }

    /**
     * @mockFile AsyncJobs
     */
    public function testListingAsyncJobsReturnsIterator()
    {
        $jobs = $this->service->listAsyncJobs();
        $this->assertInstanceOf('OpenCloud\DNS\Collection\DnsIterator', $jobs);

        $count = 0;

        foreach ($jobs as $job) {
            $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $job);
            $count++;
        }

        $this->assertEquals(2, $count);
    }

    /**
     * @mockFile AsyncJob
     */
    public function testGettingJob()
    {
        $job = $this->service->getAsyncJob('foo');

        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $job);
    }
}
