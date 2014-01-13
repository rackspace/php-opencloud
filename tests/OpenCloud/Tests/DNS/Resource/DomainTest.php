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

namespace OpenCloud\Tests\DNS\Resource;

use OpenCloud\Tests\DNS\DnsTestCase;

class DomainTest extends DnsTestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Domain', $this->domain);
    }

    public function testCreate()
    {
        $this->domain->addRecord(
            $this->domain->record(array('type' => 'A'))
        );
        $this->domain->addSubdomain(
            $this->domain->subdomain(array('name' => 'foo'))
        );
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $this->domain->Create());
    }

    public function testUpdate()
    {
        $resp = $this->domain->update(array(
            'id'           => 'TEST',
            'name'         => 'FOO',
            'emailAddress' => 'no-body@dontuseemail.com')
        );
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $resp);
    }

    public function testDelete()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $this->domain->delete());
    }

    public function testRecord()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Record', $this->domain->record());
    }

    public function testRecordList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->domain->recordList());
    }

    public function testSubdomain()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Subdomain', $this->domain->subdomain());
    }

    public function testSubdomainList()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->domain->subdomainList());
    }

    public function testAddRecord()
    {
        $rec = $this->domain->record();
        $this->assertEquals(1, $this->domain->addRecord($rec));
    }

    public function testAddSubdomain()
    {
        $sub = $this->domain->Subdomain();
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Subdomain', $sub);
        $this->assertEquals(1, $this->domain->addSubdomain($sub));
        $this->assertEquals($this->domain, $sub->getParent());
    }

    /**
     * @mockFile Domain_Changes
     */
    public function testChanges()
    {
        $this->assertInstanceOf('stdClass', $this->domain->changes());
    }

    public function testExport()
    {
        $response = $this->makeResponse('{"status":"RUNNING","verb":"POST","jobId":"52179628-6df6-46a0-bdb3-078769cd0e9d","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/1234/status/52179628-6df6-46a0-bdb3-078769cd0e9d","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/1234/domains/3586209/clone?cloneName=clone1.com"}', 202);
        $this->addMockSubscriber($response);
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $this->domain->export());
    }

    public function testCloneDomain()
    {
        $this->addMockSubscriber($this->makeResponse('{"name": "foo", "url": "foo"}'));

        $asr = $this->domain->cloneDomain('newdomain.io');
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $asr);
        
        $this->assertNotNull($asr->url());
        $this->assertEquals('jobId', $asr->primaryKeyField());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testAsyncCreateFails()
    {
        $this->domain->export()->create();
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testAsyncUpdateFails()
    {
        $this->domain->export()->update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testAsyncDeleteFails()
    {
        $this->domain->export()->delete();
    }
    
}
