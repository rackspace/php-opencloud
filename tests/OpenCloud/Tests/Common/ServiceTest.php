<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2014 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\Service\Catalog;
use OpenCloud\Common\Service\ServiceBuilder;

class ServiceTest extends \OpenCloud\Tests\OpenCloudTestCase
{

    private $service;

    public function setupObjects()
    {
        $this->service = $this->getClient()->computeService('cloudServersOpenStack', 'DFW');
    }

    public function testExtensions()
    {
        $ext = $this->service->getExtensions();
        $this->assertTrue(is_array($ext));
    }

    public function testRegion()
    {
        $this->assertEquals('DFW', $this->service->region());
    }

    public function testName()
    {
        $this->assertEquals('cloudServersOpenStack', $this->service->name());
    } 
    
    public function test_Endpoint()
    {
        $endpoint = $this->service->getEndpoint();
        $this->assertInstanceOf('OpenCloud\Common\Service\Endpoint', $endpoint);
        $this->assertEquals($this->service->getRegion(), $endpoint->getRegion());
    }
    
    public function test_Catalog()
    {
        $catalog = $this->getClient()->getCatalog();
        $this->assertEquals($catalog, Catalog::factory($catalog));
        
        foreach ($catalog->getItems() as $item) {
            $this->assertNotNull($item->getName());
            $this->assertNotNull($item->getType());
            $this->assertNotNull($item->getEndpoints());
        }
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\EndpointError
     */
    public function test_Endpoint_Error_With_Incorrect_Region()
    {
        $this->getClient()->computeService('cloudServersOpenStack', 'BERLIN');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\EndpointError
     */
    public function test_NonExistent_Service()
    {
        $this->getClient()->computeService('fooBar', 'DFW');
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\InvalidArgumentError
     */
    public function test_Catalog_Fails_Without_Correct_Type()
    {
        Catalog::factory(null);
    }

    public function test_Service()
    {
        $this->assertEquals('compute', $this->service->getType());
        $this->assertEquals('cloudServersOpenStack', $this->service->getName());
        $this->assertNotNull($this->service->limits());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\EndpointError
     */
    public function test_Endpoint_Error()
    {
        $service = $this->getClient()->computeService('testService', 'ORD');
        $service->getBaseUrl();
    }

    public function test_Region_ServiceBuilder()
    {
        $dfwService = ServiceBuilder::factory($this->getClient(), 'OpenCloud\Compute\Service', array(
            'name'    => 'cloudServersOpenStack',
            'region'  => 'DFW',
            'urltype' => 'publicURL'
        ));

        $this->assertEquals('DFW', $dfwService->getEndpoint()->getRegion());

        $dfwService2 = $this->getClient()->computeService('cloudServersOpenStack', 'SYD');
        $this->assertEquals('SYD', $dfwService2->getEndpoint()->getREgion());
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\ServiceException
     */
    public function test_ServiceBuilder_Fails_Without_Region()
    {
        $this->addMockSubscriber($this->getTestFilePath('Auth_No_Default_Region', '.'));
        $client = $this->getClient();
        $client->authenticate();

        $client->computeService('cloudServersOpenStack');
    }

    public function test_ServiceBuilder_Success_Without_Region_In_Regionless_Service()
    {
        $this->addMockSubscriber($this->getTestFilePath('Auth_No_Default_Region', '.'));
        $client = $this->getClient();
        $client->authenticate();

        $client->dnsService('cloudDNS');
        $client->cloudMonitoringService();
    }

}