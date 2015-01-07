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

use OpenCloud\OpenStack;
use OpenCloud\Rackspace;

class OpenStackTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $credentials = array('username' => 'foo', 'password' => 'bar', 'tenantName' => 'baz');

    public function __construct()
    {
        $this->client = new OpenStack(Rackspace::US_IDENTITY_ENDPOINT, $this->credentials);
        $this->client->addSubscriber(new MockSubscriber());
    }

    public function test__construct()
    {
        $client = new OpenStack(Rackspace::US_IDENTITY_ENDPOINT, $this->credentials);
    }

    public function test_Credentials()
    {
        $client = clone $this->client;

        $this->assertEquals($this->credentials, $client->getSecret());

        $this->assertEquals(
            json_encode(array('auth' => array('passwordCredentials' => array('username' => 'foo', 'password' => 'bar'), 'tenantName' => 'baz'))),
            $client->getCredentials()
        );

        $client->setSecret(array(
            'username' => 'foo', 'password' => 'bar', 'tenantId' => 'baz'
        ));

        $this->assertEquals(
            json_encode(array('auth' => array('passwordCredentials' => array('username' => 'foo', 'password' => 'bar'), 'tenantId' => 'baz'))),
            $client->getCredentials()
        );
    }

    public function test_Auth_Methods()
    {
        $this->client->authenticate();

        // catalog
        $this->assertInstanceOf('OpenCloud\Common\Service\Catalog', $this->client->getCatalog());

        // token
        $this->assertNotNull('string', gettype($this->client->getToken()));
        $this->assertInstanceOf('OpenCloud\Identity\Resource\Token', $this->client->getTokenObject());
        $this->assertEquals($this->client->getTokenObject()->getExpires(), $this->client->getExpiration());
        $this->assertEquals($this->client->getTokenObject()->hasExpired(), $this->client->hasExpired());

        // tenant
        $this->assertEquals('string', gettype($this->client->getTenant()));
        $this->assertInstanceOf('OpenCloud\Identity\Resource\Tenant', $this->client->getTenantObject());

        // misc
        $this->assertNotNull($this->client->getUrl());
    }

    public function test_Logger()
    {
        $this->assertInstanceOf(
            'Psr\Log\LoggerInterface',
            $this->client->getLogger()
        );
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\CredentialError
     */
    public function test_Credentials_Fail()
    {
        $client = new OpenStack(Rackspace::US_IDENTITY_ENDPOINT, array());
        $client->getCredentials();
    }

    public function test_Auth_Url()
    {
        $this->assertEquals(Rackspace::US_IDENTITY_ENDPOINT, (string)$this->client->getAuthUrl());

        $this->client->setAuthUrl(Rackspace::UK_IDENTITY_ENDPOINT);
        $this->assertEquals(Rackspace::UK_IDENTITY_ENDPOINT, (string)$this->client->getAuthUrl());
    }

    public function test_Factory_Methods()
    {
        $this->assertInstanceOf(
            'OpenCloud\Compute\Service',
            $this->client->computeService('cloudServersOpenStack', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\ObjectStore\Service',
            $this->client->objectStoreService('cloudFiles', 'DFW')
        );
        $this->assertInstanceOf(
            'OpenCloud\Volume\Service',
            $this->client->volumeService('cloudBlockStorage', 'DFW')
        );
    }

    public function test_User_Agent()
    {
        $this->assertEquals($this->client->getUserAgent(), $this->client->getDefaultUserAgent());
    }

    public function test_Export_Credentials()
    {
        $this->client->setExpiration(time() - 1);
        $credentials = $this->client->exportCredentials();
        $this->assertTrue(is_array($credentials));
        $this->assertCount(4, $credentials);
    }

    public function test_Import_Credentials()
    {
        $this->client->importCredentials(array(
            'token'      => '{token}',
            'expiration' => '{expiration}',
            'tenant'     => '{tenant}',
            'catalog'    => array(
                (object)array(
                    'endpoints' => array(
                        (object)array(
                            'region'      => 'DFW',
                            'publicURL'   => 'foo',
                            'internalURL' => 'bar'
                        )
                    ),
                    'name'      => 'testService',
                    'type'      => 'someServiceType'
                )
            )
        ));

        $this->assertEquals('{token}', $this->client->getToken());
        $this->assertEquals('{expiration}', $this->client->getExpiration());
        $this->assertEquals('{tenant}', $this->client->getTenant());
    }

    public function test_Import_Credentials_Numeric_Tenant()
    {
        $randomNumericTenant = (string)mt_rand();
        $this->client->importCredentials(array(
            'token'      => '{token}',
            'expiration' => '{expiration}',
            'tenant'     => $randomNumericTenant,
            'catalog'    => array(
                (object)array(
                    'endpoints' => array(
                        (object)array(
                            'region'      => 'DFW',
                            'publicURL'   => 'foo',
                            'internalURL' => 'bar'
                        )
                    ),
                    'name'      => 'testService',
                    'type'      => 'someServiceType'
                )
            )
        ));

        $this->assertEquals('{token}', $this->client->getToken());
        $this->assertEquals('{expiration}', $this->client->getExpiration());
        $this->assertEquals($randomNumericTenant, $this->client->getTenant());
    }

    public function testLoggerServiceInjection()
    {
        // Create a new client, pass stub via constructor options argument
        $stubLogger = $this->getMock('Psr\Log\NullLogger');
        $client = new OpenStack(Rackspace::US_IDENTITY_ENDPOINT, $this->credentials, array(
            'logger' => $stubLogger,
        ));
        $client->addSubscriber(new MockSubscriber());

        // Test all OpenStack factory methods on proper Logger service injection
        $service = $client->objectStoreService('cloudFiles', 'DFW');
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $service->getCdnService();
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $client->computeService('cloudServersOpenStack', 'DFW');
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $client->orchestrationService(null, 'DFW');
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $client->volumeService('cloudBlockStorage', 'DFW');
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $client->identityService();
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $client->imageService('cloudImages', 'IAD');
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));

        $service = $client->networkingService(null, 'IAD');
        $this->assertContains("Mock_NullLogger", get_class($service->getLogger()));
    }
}
