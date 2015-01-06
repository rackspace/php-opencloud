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

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\OpenCloudTestCase;

class CDNTestCase extends OpenCloudTestCase
{
    protected $service;

    public function setupObjects()
    {
        $this->service = $this->getClient()->cdnService();

        $this->addMockSubscriber($this->makeResponse('{"name":"mywebsite.com","domains":[{"domain":"blog.mywebsite.com"}],"origins":[{"origin":"mywebsite.com","port":80,"ssl":false},{"origin":"77.66.55.44","port":80,"ssl":false,"rules":[{"name":"videos","request_url":"^/videos/*.m3u"}]}],"caching":[{"name":"default","ttl":3600},{"name":"home","ttl":17200,"rules":[{"name":"index","request_url":"/index.htm"}]},{"name":"images","ttl":12800,"rules":[{"name":"images","request_url":"*.png"}]}],"restrictions":[{"name":"website only","rules":[{"name":"mywebsite.com","http_host":"www.mywebsite.com"}]}],"flavor_id":"cdn","status":"deployed","links":[{"href":"https://global.cdn.api.rackspacecloud.com/v1.0/services/mywebsite.com","rel":"self"},{"href":"mywebsite.com","rel":"access_url"}]}'));
        $this->serviceResource = $this->service->getService('mywebsite.com');
    }

    protected function assertIsService($object)
    {
        $this->assertInstanceOf('OpenCloud\CDN\Service', $object);
    }

    protected function assertIsServiceResource($object)
    {
        $this->assertInstanceOf('OpenCloud\CDN\Resource\Service', $object);
    }

    protected function assertIsFlavorResource($object)
    {
        $this->assertInstanceOf('OpenCloud\CDN\Resource\Flavor', $object);
    }
}
