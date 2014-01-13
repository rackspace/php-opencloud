<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests;


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
        $this->assertInstanceOf(
            'OpenCloud\Database\Service', 
            $this->getClient()->databaseService('cloudDatabases', 'DFW')
        );
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