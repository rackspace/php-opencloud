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
use OpenCloud\DNS\Resource\Domain;
use OpenCloud\DNS\Resource\Record;
use OpenCloud\DNS\Service;
use OpenCloud\Tests\StubConnection;

class RecordTest extends PHPUnit_Framework_TestCase
{

    private $domain;
    private $record;

    public function __construct()
    {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $dns = new Service($conn, 'cloudDNS', array('N/A'), 'publicURL');
        $this->domain = new Domain($dns);
        $this->record = new Record($this->domain);
    }

    /**
     * Tests
     */
    public function test__construct()
    {
        $this->record = new Record($this->domain, array(
            'type' => 'A', 
            'ttl' => 60, 
            'data' => '1.2.3.4'
        ));
        $this->assertInstanceOf(
            'OpenCloud\DNS\Resource\Record', 
            $this->record
        );
    }

    public function testParent()
    {
        $this->assertEquals($this->domain, $this->record->Parent());
    }

}
