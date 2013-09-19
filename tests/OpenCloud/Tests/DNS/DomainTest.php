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
use OpenCloud\Tests\StubConnection;
use OpenCloud\DNS\Service;
use OpenCloud\DNS\Resource\Domain;

class DomainTest extends PHPUnit_Framework_TestCase
{

    private $conn;   // connection
    private $dns;
    private $domain; // compute service

    public function __construct()
    {
        $this->conn = new StubConnection('http://example.com', 'SECRET');
        $this->dns = new Service(
            $this->conn, 'cloudDNS', array('N/A'), 'publicURL'
        );
        $this->domain = new Domain($this->dns);
        $this->domain->id = 'DOMAIN-ID';
    }

    /**
     * Tests
     */
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
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->domain->recordList());
    }

    public function testSubdomain()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\Subdomain', $this->domain->subdomain());
    }

    public function testSubdomainList()
    {
        $this->assertInstanceOf('OpenCloud\Common\Collection', $this->domain->subdomainList());
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
        $this->assertEquals(1, $this->domain->AddSubdomain($sub));
        $this->assertEquals($this->domain, $sub->Parent());
    }

    public function testChanges()
    {
        $this->assertInstanceOf('stdClass', $this->domain->Changes());
    }

    public function testExport()
    {
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $this->domain->export());
    }

    public function testCloneDomain()
    {
        $asr = $this->domain->cloneDomain('newdomain.io');
        $this->assertInstanceOf('OpenCloud\DNS\Resource\AsyncResponse', $asr);
        
        $this->assertNotNull($asr->url());
        $this->assertNotNull($asr->name());
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
