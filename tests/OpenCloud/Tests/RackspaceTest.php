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

namespace OpenCloud\Tests;

use OpenCloud\Tests\MockLogger;

class RackspaceTest extends OpenCloudTestCase
{
    const CREDENTIALS = <<<EOT
{"auth":{"RAX-KSKEY:apiKeyCredentials":{"username":"foo","apiKey":"bar"}}}
EOT;

    public function test_Credentials()
    {
        $this->assertEquals(self::CREDENTIALS, $this->getClient()->getCredentials());
    }

    public function test_Factory_Methods()
    {
        // Inject mock logger
        $oldLogger = $this->getClient()->getLogger();
        $this->getClient()->setLogger(new MockLogger());

        $this->assertInstanceOf(
            'OpenCloud\Database\Service',
            $this->getClient()->databaseService('cloudDatabases', 'DFW')
        );

        // Re-inject old logger
        $this->getClient()->setLogger($oldLogger);

        $this->assertInstanceOf(
            'OpenCloud\LoadBalancer\Service',
            $this->getClient()->loadBalancerService('cloudLoadBalancers', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\DNS\Service',
            $this->getClient()->dnsService('cloudDNS', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\CloudMonitoring\Service',
            $this->getClient()->cloudMonitoringService('cloudMonitoring', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\Autoscale\Service',
            $this->getClient()->autoscaleService('autoscale', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\Queues\Service',
            $this->getClient()->queuesService('cloudQueues', 'ORD')
        );
    }
}
