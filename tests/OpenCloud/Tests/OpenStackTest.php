<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
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
        
        $this->assertNotNull($this->client->getExpiration());
        $this->assertNotNull($this->client->getToken());
        $this->assertNotNull($this->client->getCatalog());
        $this->assertNotNull($this->client->getTenant());
        /* ??? */
        $this->assertNotNull($this->client->getUrl());
        $this->assertTrue($this->client->hasExpired());
    }
    
    public function test_Logger()
    {
        $this->assertInstanceOf(
            'OpenCloud\Common\Log\LoggerInterface',
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
        $this->assertEquals(Rackspace::US_IDENTITY_ENDPOINT . 'tokens', (string) $this->client->getAuthUrl());
        
        $this->client->setAuthUrl(Rackspace::UK_IDENTITY_ENDPOINT);
        $this->assertEquals(Rackspace::UK_IDENTITY_ENDPOINT . 'tokens', (string) $this->client->getAuthUrl());
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
                (object) array(
                    'endpoints' => array(
                        (object) array(
                            'region'      => 'DFW',
                            'publicURL'   => 'foo',
                            'internalURL' => 'bar'
                        )
                    ),
                    'name' => 'testService',
                    'type' => 'someServiceType'
                )
            )
        ));

        $this->assertEquals('{token}', $this->client->getToken());
        $this->assertEquals('{expiration}', $this->client->getExpiration());
        $this->assertEquals('{tenant}', $this->client->getTenant());
    }
    
}