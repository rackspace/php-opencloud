<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud;

use OpenCloud\Common\Http\Client;
use OpenCloud\Common\Http\Message\RequestSubscriber;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Service\ServiceBuilder;
use OpenCloud\Common\Service\Catalog;
use OpenCloud\Common\Http\Message\RequestFactory;
use Guzzle\Common\Collection;

class OpenStack extends Client
{
    private $secret = array();
    private $token;
    private $expiration;
    private $tenant;
    private $catalog;
    private $logger;
    
    public function __construct($url, array $secret, array $options = array())
    {
        $this->getLogger()->info(Lang::translate('Initializing OpenStack client'));

        $this->setSecret($secret);

        $this->setRequestFactory(RequestFactory::getInstance());
        
        parent::__construct($url, $options);
        
        $this->defaultHeaders = new Collection(
            array('Content-Type' => 'application/json')
        );
        
        $this->addSubscriber(RequestSubscriber::getInstance());
    }
        
    /**
     * Set the secret for the client.
     * 
     * @param  array $secret
     * @return OpenCloud\OpenStack
     */
    public function setSecret(array $secret = array())
    {
        $this->secret = $secret;
        
        return $this;
    }
    
    /**
     * Get the secret.
     * 
     * @return array
     */
    public function getSecret()
    {
        return $this->secret;
    }
    
    /**
     * Set the token for this client.
     * 
     * @param  string $token
     * @return OpenCloud\OpenStack
     */
    public function setToken($token)
    {
        $this->token = $token;
        
        return $this;
    }
    
    /**
     * Get the token for this client.
     * 
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * Set the expiration for this token.
     * 
     * @param  int $expiration
     * @return OpenCloud\OpenStack
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
        
        return $this;
    }
    
    /**
     * Get the expiration time.
     * 
     * @return int
     */
    public function getExpiration()
    {
        return $this->expiration;
    }
    
    /**
     * Set the tenant for this client.
     * 
     * @param  string $tenant
     * @return OpenCloud\OpenStack
     */
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
       
        return $this;
    }
    
    /**
     * Get the tenant for this client.
     * 
     * @return string
     */
    public function getTenant()
    {
        return $this->tenant;
    }
    
    /**
     * Set the service catalog.
     * 
     * @param  mixed $catalog
     * @return OpenCloud\OpenStack
     */
    public function setCatalog($catalog)
    {
        $this->catalog = Catalog::factory($catalog);

        return $this;
    }
    
    /**
     * Get the service catalog.
     * 
     * @return array
     */
    public function getCatalog()
    {
        return $this->catalog;
    }
    
    public function setLogger(Common\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        if (null === $this->logger) {
            $this->setLogger(new Common\Log\Logger);
        }
        return $this->logger;
    }
    
    /**
     * Get the items to be exported.
     * 
     * @return array
     */
    public function getExportItems()
    {
        return $this->exportItems;
    }
    
    /**
     * Checks whether token has expired.
     * 
     * @return bool
     */
    public function hasExpired()
    {
        return !$this->expiration || time() > ($this->expiration - RAXSDK_FUDGE);
    }

    /**
     * Creates and returns the formatted credentials to POST to the auth
     * service.
     *
     * @return string
     */
    public function getCredentials()
    {
        if (!empty($this->secret['username']) && !empty($this->secret['password'])) {
            
            $credentials = array(
                'auth' => array(
                    'passwordCredentials' => array(
                        'username' => $this->secret['username'],
                        'password' => $this->secret['password']
                    )
                )
            );

            if (isset($this->secret['tenantName'])) {
                $credentials['auth']['tenantName'] = $this->secret['tenantName'];
            }

            return json_encode($credentials);
            
        } else {
            throw new Exceptions\CredentialError(
               Lang::translate('Unrecognized credential secret')
            );
        }
    }

    /**
     * Authenticates using the supplied credentials
     *
     * @api
     * @return void
     * @throws AuthenticationError
     */
    public function authenticate()
    {
        $response = $this->post('tokens', null, $this->getCredentials())->send();
        $object = $response->getDecodedBody();

        // Save the token information as well as the ServiceCatalog
        $this->setToken($object->access->token->id);
        $this->setExpiration(strtotime($object->access->token->expires));
        $this->setCatalog($object->access->serviceCatalog);
        
        // Add to default headers for future reference
        $this->defaultHeaders['X-Auth-Token'] = (string) $this->getToken();
        
        /**
         * In some cases, the tenant name/id is not returned
         * as part of the auth token, so we check for it before
         * we set it. This occurs with pure Keystone, but not
         * with the Rackspace auth.
         */
        if (isset($object->access->token->tenant)) {
            $this->setTenant($object->access->token->tenant->id);
        }
    }

    /**
     * exports saved token, expiration, tenant, and service catalog as an array
     *
     * This could be stored in a cache (APC or disk file) and reloaded using
     * ImportCredentials()
     *
     * @return array
     */
    public function exportCredentials()
    {
        if ($this->hasExpired()) {
            $this->authenticate();
        }
        return array(
            'token'      => $this->getToken(),
            'expiration' => $this->getExpiration(),
            'tenant'     => $this->getTenant(),
            'catalog'    => $this->getCatalog()
        );
    }

    /**
     * imports credentials from an array
     *
     * Takes the same values as ExportCredentials() and reuses them.
     *
     * @return void
     */
    public function importCredentials(array $values)
    {
        if (!empty($values['token'])) {
            $this->setToken($values['token']);
        }
        if (!empty($values['expiration'])) {
            $this->setExpiration($values['expiration']);
        }
        if (!empty($values['tenant'])) {
            $this->setTenant($values['tenant']);
        }
        if (!empty($values['catalog'])) {
            $this->setCatalog($values['catalog']);
        }
    }
    
    public function getUrl()
    {
        return $this->getBaseUrl();
    }

    /**
     * Creates a new ObjectStore object (Swift/Cloud Files)
     *
     * @api
     * @param string $name the name of the Object Storage service to attach to
     * @param string $region the name of the region to use
     * @param string $urltype the URL type (normally "publicURL")
     * @return ObjectStore
     */
    public function objectStore($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'ObjectStore', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

    /**
     * Creates a new Compute object (Nova/Cloud Servers)
     *
     * @api
     * @param string $name the name of the Compute service to attach to
     * @param string $region the name of the region to use
     * @param string $urltype the URL type (normally "publicURL")
     * @return Compute
     */
    public function compute($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'Compute', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

    /**
     * Creates a new Orchestration (heat) service object
     *
     * @api
     * @param string $name the name of the Compute service to attach to
     * @param string $region the name of the region to use
     * @param string $urltype the URL type (normally "publicURL")
     * @return Orchestration\Service
     * @codeCoverageIgnore
     */
    public function orchestration($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'Orchestration', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

    /**
     * Creates a new VolumeService (cinder) service object
     *
     * This is a factory method that is Rackspace-only (NOT part of OpenStack).
     *
     * @param string $name the name of the service (e.g., 'cloudBlockStorage')
     * @param string $region the region (e.g., 'DFW')
     * @param string $urltype the type of URL (e.g., 'publicURL');
     */
    public function volumeService($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'Volume', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

}
