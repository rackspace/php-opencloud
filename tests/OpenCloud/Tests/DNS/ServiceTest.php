<?php


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
            (string) $this->service->getUrl()
        );
    }

    public function testDomain()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Domain', $this->service->domain());
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

}
