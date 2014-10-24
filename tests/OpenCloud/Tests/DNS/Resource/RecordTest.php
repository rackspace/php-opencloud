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
