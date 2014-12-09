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

class EndpointTest extends OpenCloudTestCase
{
    public function testGetVersionedUrlWithVersionLessEndpointSupportedVersionFound()
    {
        $this->addMockSubscriber($this->makeResponse('{"versions":[{"status":"CURRENT","id":"v2.0","links":[{"href":"http://hostport/v2.0","rel":"self" }]}]}', 200));
        
        $endpoint = new Endpoint();

        $expectedUrl = "http://hostport/v2.0";
        $actualUrl   = $this->invokeMethod(
            $endpoint,
            'getVersionedUrl',
            array('http://hostport', 'v2.0', $this->client)
        );

        $this->assertInstanceOf('Guzzle\Http\Url', $actualUrl);
        $this->assertEquals($expectedUrl, $actualUrl);
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function testGetVersionedUrlWithVersionLessEndpointSupportedVersionNotFound()
    {
        $this->addMockSubscriber($this->makeResponse('{"versions":[{"status":"CURRENT","id":"v2.0","links":[{"href":"http://hostport/v2.0","rel":"self" }]}]}', 200));
        
        $endpoint = new Endpoint();

        $this->invokeMethod(
            $endpoint,
            'getVersionedUrl',
            array('http://hostport', 'v2.1', $this->client)
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UnsupportedVersionError
     */
    public function testGetVersionedUrlWithVersionLessEndpointInvalidResponse()
    {
        $this->addMockSubscriber($this->makeResponse('{}'));
        
        $endpoint = new Endpoint();

        $this->invokeMethod(
            $endpoint,
            'getVersionedUrl',
            array('http://hostport', 'v2.1', $this->client)
        );
    }

    public function testGetVersionedUrlWithVersionLessEndpointSupportedVersionNotSpecified()
    {
        $endpoint = new Endpoint();

        $expectedUrl = "http://hostport";
        $actualUrl   = $this->invokeMethod(
            $endpoint,
            'getVersionedUrl',
            array($expectedUrl, null, $this->client)
        );

        $this->assertEquals($expectedUrl, $actualUrl);
    }

    public function testGetVersionedUrlWithVersionedEndpointUrl()
    {
        $this->addMockSubscriber($this->makeResponse('{}', 200));
        
        $endpoint = new Endpoint();

        $expectedUrl = "http://hostport/v1";
        $actualUrl   = $this->invokeMethod(
            $endpoint,
            'getVersionedUrl',
            array("http://hostport/v1", null, $this->client)
        );

        $this->assertInstanceOf('Guzzle\Http\Url', $actualUrl);
        $this->assertEquals($expectedUrl, $actualUrl);
    }
}
