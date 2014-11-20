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

namespace OpenCloud\Tests\Common\Service;

use OpenCloud\Common\Service\Endpoint;
use OpenCloud\Tests\OpenCloudTestCase;
use OpenCloud\Common\Http\Client;

class PublicEndpoint extends Endpoint
{
    public function getVersionedUrl($url, $supportedServiceVersion, Client $client)
    {
        return parent::getVersionedUrl($url, $supportedServiceVersion, $client);
    }
}

class EndpointTest extends OpenCloudTestCase
{
    public function testGetVersionedUrlWithVersionLessEndpointSupportedVersionFound()
    {
        $this->addMockSubscriber($this->makeResponse('{"versions":[{"status":"CURRENT","id":"v2.0","links":[{"href":"http://hostport/v2.0","rel":"self" }]}]}', 200));
        
        $e = new PublicEndpoint();

        $expectedUrl = "http://hostport/v2.0";
        $this->assertEquals($expectedUrl, $e->getVersionedUrl("http://hostport", 'v2.0', $this->client));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function testGetVersionedUrlWithVersionLessEndpointSupportedVersionNotFound()
    {
        $this->addMockSubscriber($this->makeResponse('{"versions":[{"status":"CURRENT","id":"v2.0","links":[{"href":"http://hostport/v2.0","rel":"self" }]}]}', 200));
        
        $e = new PublicEndpoint();

        $e->getVersionedUrl("http://hostport", 'v2.1', $this->client);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function testGetVersionedUrlWithVersionLessEndpointInvalidResponse()
    {
        $this->addMockSubscriber($this->makeResponse('{}'));
        
        $e = new PublicEndpoint();

        $e->getVersionedUrl("http://hostport", 'v2.1', $this->client);
    }

    public function testGetVersionedUrlWithVersionedEndpointUrl()
    {
        $this->addMockSubscriber($this->makeResponse('{}', 200));
        
        $e = new PublicEndpoint();

        $expectedUrl = "http://hostport/v1";
        $this->assertEquals($expectedUrl, $e->getVersionedUrl("http://hostport/v1", 'unknown', $this->client));
    }
}
