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

class RecordTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $service;
    private $domain;
    private $record;

    public function __construct()
    {
        $service = $this->getClient()->dns('cloudDNS', 'N/A', 'publicURL');
        $this->domain = $service->domain();
        $this->record = $this->domain->record();
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $record = $this->domain->record(array(
            'type' => 'A', 
            'ttl'  => 60, 
            'data' => '1.2.3.4'
        ));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\Record', 
            $record
        );
        $this->assertEquals('1.2.3.4', $record->data);
    }

    public function testParent()
    {
        $this->assertEquals($this->domain, $this->record->getParent());
    }

}
