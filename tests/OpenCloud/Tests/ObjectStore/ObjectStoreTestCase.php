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

namespace OpenCloud\Tests\ObjectStore;

use Guzzle\Http\Message\Response;
use OpenCloud\Tests\OpenCloudTestCase;

class ObjectStoreTestCase extends OpenCloudTestCase
{
    protected $service;
    protected $container;

    protected $mockPath = 'ObjectStore';

    public function setupObjects()
    {
        $this->service = $this->getClient()->objectStoreService();

        $response1 = new Response(204, array(
            'X-Container-Object-Count' => '5',
            'X-Trans-Id'               => 'tx30e27bcc8bf34c0ebfdf078337895478',
            'X-Timestamp'              => '1331584412.96818',
            'X-Container-Meta-Book'    => 'MobyDick',
            'X-Container-Meta-Subject' => 'Whaling',
            'X-Container-Bytes-Used'   => '3846773'
        ));

        $this->addMockSubscriber($response1);

        $this->container = $this->service->getContainer('foo');
    }

    protected function setupCdnContainerMockResponse()
    {
        $response = new Response(204, array(
            'X-Cdn-Ssl-Uri'       => 'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
            'X-Ttl'               => '259200',
            'X-Cdn-Uri'           => 'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
            'X-Cdn-Enabled'       => 'True',
            'X-Log-Retention'     => 'False',
            'X-Cdn-Streaming-Uri' => 'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
            'X-Trans-Id'          => 'tx82a6752e00424edb9c46fa2573132e2c'
        ));

        $this->addMockSubscriber($response);
    }
}
