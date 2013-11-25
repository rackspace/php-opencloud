<?php

namespace OpenCloud\Tests\DNS\Resource;

use OpenCloud\Tests\DNS\DnsTestCase;

class RecordTest extends DnsTestCase
{

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

}
