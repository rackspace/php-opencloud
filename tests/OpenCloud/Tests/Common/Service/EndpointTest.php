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

class PublicEndpoint extends Endpoint
{
    public function getVersionedUrl($url, $client)
    {
        return parent::getVersionedUrl($url, $client);
    }
}

class EndpointTest extends OpenCloudTestCase
{
    public function testGetVersionedUrlWithVersionLessEndpointUrl()
    {
        $this->addMockSubscriber($this->makeResponse('{"versions":[{"status":"CURRENT","id":"v2.0","links":[{"href":"http://hostport/v2.0","rel":"self" }]}]}', 200));
        
        $e = new PublicEndpoint();

        $expectedUrl = "http://hostport/v2.0";
        $this->assertEquals($expectedUrl, $e->getVersionedUrl("http://hostport", $this->client));
    }

    public function testGetVersionedUrlWithVersionedEndpointUrl()
    {
        $this->addMockSubscriber($this->makeResponse('{}', 200));
        
        $e = new PublicEndpoint();

        $expectedUrl = "http://hostport/v1";
        $this->assertEquals($expectedUrl, $e->getVersionedUrl("http://hostport/v1", $this->client));
    }
}
