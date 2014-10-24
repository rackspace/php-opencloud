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

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class ZoneTest extends CloudMonitoringTestCase
{
    public function setupObjects()
    {
        $this->service = $this->getClient()->cloudMonitoringService();

        $response = new Response(200, array('Content-Type' => 'application/json'), '{"id":"mzAAAAA","label":"US South (Atlanta) - 5","country_code":"US","source_ips":["1.2.0.0/24"]}');
        $this->addMockSubscriber($response);

        $this->resource = $this->service->getMonitoringZone('mzAAAAA');
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Zone',
            $this->resource
        );
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/monitoring_zones/mzAAAAA',
            (string)$this->resource->getUrl()
        );
    }

    public function testCollection()
    {
        $response = new Response(200, array('Content-Type' => 'application/json'), '{"values":[{"id":"mzAAAAA","label":"US South (Atlanta) - 5","country_code":"US","source_ips":["1.2.0.0/24"]}],"metadata":{"count":1,"limit":50,"marker":null,"next_marker":null,"next_href":null}}');
        $this->addMockSubscriber($response);

        $list = $this->service->getMonitoringZones();

        $this->assertInstanceOf(self::COLLECTION_CLASS, $list);

        $first = $list->first();

        $this->assertEquals('mzAAAAA', $first->getId());
        $this->assertEquals('US', $first->getCountryCode());
    }

    public function testGetClass()
    {
        $this->assertEquals('mzAAAAA', $this->resource->getId());
    }

    /**
     * @mockFile Zone_TraceRoute
     */
    public function testTraceroute()
    {
        $object = $this->resource->traceroute(array(
            'target'          => 'http://test.com',
            'target_resolver' => 'foo'
        ));

        $this->assertNotNull($object);
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\ZoneException
     */
    public function testTracerouteFailsWithoutId()
    {
        $this->resource->traceroute(array());
    }

    /**
     * @expectedException OpenCloud\CloudMonitoring\Exception\ZoneException
     */
    public function testTracerouteFailsWithoutTarget()
    {
        $this->resource->setId('mzAAAAA');
        $this->resource->traceroute(array());
    }
}
