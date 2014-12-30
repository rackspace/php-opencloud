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

namespace OpenCloud\Tests\CDN;

use OpenCloud\CDN\Service;
use OpenCloud\Common\Exceptions\InvalidTemplateError;

class ServiceTest extends CDNTestCase
{
    public function test__construct()
    {
        $service = $this->getClient()->cdnService();
        $this->assertIsService($service);
    }

    public function testCreateService()
    {
        $this->assertIsServiceResource($this->service->createService(array(
            'name' => 'mywebsite'
        )));
    }

    public function testListServices()
    {
        $this->addMockSubscriber($this->makeResponse('{"links":[{"rel":"next","href":"https://global.cdn.api.rackspacecloud.com/v1.0/services?marker=www.myothersite.com&limit=20"}],"services":[{"name":"mywebsite.com","domains":[{"domain":"www.mywebsite.com"}],"origins":[{"origin":"mywebsite.com","port":80,"ssl":false}],"caching":[{"name":"default","ttl":3600},{"name":"home","ttl":17200,"rules":[{"name":"index","request_url":"/index.htm"}]},{"name":"images","ttl":12800,"rules":[{"name":"images","request_url":"*.png"}]}],"restrictions":[{"name":"website only","rules":[{"name":"mywebsite.com","http_host":"www.mywebsite.com"}]}],"flavor_id":"cdn","status":"deployed","links":[{"href":"https://global.cdn.api.rackspacecloud.com/v1.0/services/mywebsite.com","rel":"self"},{"href":"mywebsite.com","rel":"access_url"}]},{"name":"myothersite.com","domains":[{"domain":"www.myothersite.com"}],"origins":[{"origin":"44.33.22.11","port":80,"ssl":false},{"origin":"77.66.55.44","port":80,"ssl":false,"rules":[{"name":"videos","request_url":"^/videos/*.m3u"}]}],"caching":[{"name":"default","ttl":3600}],"restrictions":[{}],"flavor_id":"cdn","status":"deployed","links":[{"href":"https://global.cdn.api.rackspacecloud.com/v1.0/services/myothersite.com","rel":"self"},{"href":"myothersite.com","rel":"access_url"}]}]}'));

        $services = $this->service->listServices();
        $this->isCollection($services);
        $this->assertIsServiceResource($services->getElement(0));
    }

    public function testGetService()
    {
        $this->addMockSubscriber($this->makeResponse('{"name":"mywebsite.com","domains":[{"domain":"blog.mywebsite.com"}],"origins":[{"origin":"mywebsite.com","port":80,"ssl":false},{"origin":"77.66.55.44","port":80,"ssl":false,"rules":[{"name":"videos","request_url":"^/videos/*.m3u"}]}],"caching":[{"name":"default","ttl":3600},{"name":"home","ttl":17200,"rules":[{"name":"index","request_url":"/index.htm"}]},{"name":"images","ttl":12800,"rules":[{"name":"images","request_url":"*.png"}]}],"restrictions":[{"name":"website only","rules":[{"name":"mywebsite.com","http_host":"www.mywebsite.com"}]}],"flavor_id":"cdn","status":"deployed","links":[{"href":"https://global.cdn.api.rackspacecloud.com/v1.0/services/mywebsite.com","rel":"self"},{"href":"mywebsite.com","rel":"access_url"}]}'));

        $service = $this->service->getService('mywebsite.com');
        $this->assertIsServiceResource($service);
        $this->assertEquals('mywebsite.com', $service->getName());
        $this->assertEquals('cdn', $service->getFlavorId());
    }

    public function testCreateFlavor()
    {
        $this->assertIsFlavorResource($this->service->createFlavor(array(
            'id' => 'asia'
        )));
    }

    public function testListFlavors()
    {
        $this->addMockSubscriber($this->makeResponse('{"flavors":[{"id":"cdn","providers":[{"provider":"akamai","links":[{"href":"http://www.akamai.com","rel":"provider_url"}]}],"links":[{"href":"https://global.cdn.api.rackspacecloud.com/v1.0/flavors/cdn","rel":"self"}]} ]}'));

        $flavors = $this->service->listFlavors();
        $this->isCollection($flavors);
        $this->assertIsFlavorResource($flavors->getElement(0));
    }

    public function testGetFlavor()
    {
        $this->addMockSubscriber($this->makeResponse('{"id":"cdn","providers":[{"provider":"akamai","links":[{"href":"http://www.akamai.com","rel":"provider_url"}]}],"links":[{"href":"http://preview.cdn.api.rackspacecloud.com/v1.0/flavors/cdn","rel":"self"}]}'));

        $flavor = $this->service->getFlavor('cdn');
        $this->assertIsFlavorResource($flavor);
        $this->assertEquals('cdn', $flavor->getId());

        $providers = $flavor->getProviders();
        $this->assertEquals('akamai', $providers[0]->provider);
    }

    public function testGetHomeDocument()
    {
        $this->addMockSubscriber($this->makeResponse('{"resources":{"rel/cdn":{"href-template":"services{?marker,limit}","href-vars":{"marker":"param/marker","limit":"param/limit"},"hints":{"allow":["GET"],"formats":{"application/json":{}}}}}}'));

        $homeDocument = $this->service->getHomeDocument();
        $this->assertNotEmpty($homeDocument);
        $this->assertEquals("services{?marker,limit}", $homeDocument->resources->{"rel/cdn"}->{"href-template"});
    }

    public function testGetPing()
    {
        $this->addMockSubscriber($this->makeResponse(null, 204));

        $ping = $this->service->getPing();
        $this->assertEquals(204, $ping->getStatusCode());
    }
}
