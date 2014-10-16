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

namespace OpenCloud\Tests\Orchestration\Resource;

use OpenCloud\Orchestration\Resource\Resource;
use OpenCloud\Tests\Orchestration\OrchestrationTestCase;

class ResourceTest extends OrchestrationTestCase
{
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCannotCreate()
    {
        $this->resource->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testCannotUpdate()
    {
        $this->resource->update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testCannotDelete()
    {
        $this->resource->delete();
    }

    public function testGetMetadata()
    {
        $this->addMockSubscriber($this->makeResponse('{"metadata":{"foo":"bar","baz":17}}'));
        $metadata = $this->resource->getMetadata();
        $this->assertEquals("bar", $metadata->foo);
    }

    public function testListEvents()
    {
        $this->addMockSubscriber($this->makeResponse('{"events":[{"resource_name":"mysql_server","event_time":"2014-07-23T08:14:47Z","links":[{"href":"http://192.168.123.200:8004/v1/dc4b074874244f7693dd65583733a758/stacks/teststack/db467ed1-50b5-4a3e-aeb1-396ff1d151c5/resources/mysql_server/events/474bfdf0-a450-46ec-a78a-0c7faa404073","rel":"self"},{"href":"http://192.168.123.200:8004/v1/dc4b074874244f7693dd65583733a758/stacks/teststack/db467ed1-50b5-4a3e-aeb1-396ff1d151c5/resources/mysql_server","rel":"resource"},{"href":"http://192.168.123.200:8004/v1/dc4b074874244f7693dd65583733a758/stacks/teststack/db467ed1-50b5-4a3e-aeb1-396ff1d151c5","rel":"stack"}],"logical_resource_id":"mysql_server","resource_status":"CREATE_FAILED","resource_status_reason":"NotFound: Subnet f8a699d0-3537-429e-87a5-6b5a8d0c2bf0 could not be found","physical_resource_id":null,"id":"474bfdf0-a450-46ec-a78a-0c7faa404073"},{"resource_name":"mysql_server","event_time":"2014-07-23T08:14:47Z","links":[{"href":"http://192.168.123.200:8004/v1/dc4b074874244f7693dd65583733a758/stacks/teststack/db467ed1-50b5-4a3e-aeb1-396ff1d151c5/resources/mysql_server/events/66fa95b6-e6f8-4f05-b1af-e828f5aba04c","rel":"self"},{"href":"http://192.168.123.200:8004/v1/dc4b074874244f7693dd65583733a758/stacks/teststack/db467ed1-50b5-4a3e-aeb1-396ff1d151c5/resources/mysql_server","rel":"resource"},{"href":"http://192.168.123.200:8004/v1/dc4b074874244f7693dd65583733a758/stacks/teststack/db467ed1-50b5-4a3e-aeb1-396ff1d151c5","rel":"stack"}],"logical_resource_id":"mysql_server","resource_status":"CREATE_IN_PROGRESS","resource_status_reason":"state changed","physical_resource_id":null,"id":"66fa95b6-e6f8-4f05-b1af-e828f5aba04c"}]}'));

        $events = $this->resource->listEvents();
        $this->isCollection($events);

        $firstEvent = $events->getElement(0);
        $this->assertIsEvent($firstEvent);
        $this->assertEquals('474bfdf0-a450-46ec-a78a-0c7faa404073', $firstEvent->getId());
    }

    public function testGetEvent()
    {
        $this->addMockSubscriber($this->makeResponse('{"event":{"event_time":"2014-01-31T20:08:15Z","id":"f4874455-6505-42b4-af84-114bba2483a5","links":[{"href":"http://hostname/v1/1234/stacks/mystack/56789/resources/lb/events/f4874455-6505-42b4-af84-114bba2483a5","rel":"self"},{"href":"http://hostname/v1/1234/stacks/mystack/56789/resources/lb","rel":"resource"},{"href":"http://hostname/v1/1234/stacks/mystack/56789","rel":"stack"}],"logical_resource_id":"lb","physical_resource_id":"098765","resource_name":"lb","resource_properties":{"accessList":null,"algorithm":"LEAST_CONNECTIONS","connectionLogging":null,"connectionThrottle":{"maxConnectionRate":50,"maxConnections":50,"minConnections":50,"rateInterval":50},"contentCaching":"ENABLED","errorPage":null,"halfClosed":false,"healthMonitor":{"attemptsBeforeDeactivation":3,"bodyRegex":".","delay":10,"hostHeader":null,"path":"/","statusRegex":".","timeout":10,"type":"HTTP"},"metadata":null,"name":"lb-Wordpress Webserver","nodes":[{"addresses":["1.2.3.4","4.5.6.7"],"condition":"ENABLED","port":80,"type":null,"weight":null}],"port":80,"protocol":"HTTP","sessionPersistence":"HTTP_COOKIE","sslTermination":null,"timeout":120,"virtualIps":[{"ipVersion":"IPV4","type":"PUBLIC"}]},"resource_status":"CREATE_COMPLETE","resource_status_reason":"state changed","resource_type":"Rackspace::Cloud::LoadBalancer"}}'));

        $event = $this->resource->getEvent('f4874455-6505-42b4-af84-114bba2483a5');
        $this->assertEquals('Rackspace::Cloud::LoadBalancer', $event->getResourceType());
    }
}
