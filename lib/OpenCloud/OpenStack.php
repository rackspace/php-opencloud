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
use Guzzle\Http\Url;

class OpenStack extends Client
{
    private $secret = array();
    private $token;
    private $expiration;
    private $tenant;
    private $catalog;
    private $logger;
    private $authUrl;
    
    public function __construct($url, array $secret, array $options = array())
    {
        $this->getLogger()->info(Lang::translate('Initializing OpenStack client'));

        $this->setSecret($secret);
        $this->setAuthUrl($url);

        $this->setRequestFactory(RequestFactory::getInstance());
        
        parent::__construct($url, $options);
        
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
           
            return json_encode(array(
                'auth' => array(
                    'passwordCredentials' => $this->secret
                )
            ));
            
        } else {
            throw new Exceptions\CredentialError(
               Lang::translate('Unrecognized credential secret')
            );
        }
    }
    
    public function setAuthUrl($url)
    {
	    $this->authUrl = $url;
	    return $this;
    }
    
    public function getAuthUrl()
    {
	    return Url::factory($this->authUrl)->addPath('tokens');
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
        $headers = array('Content-Type' => 'application/json');
        
        $object = $this->post($this->getAuthUrl(), $headers, $this->getCredentials())
            ->send()
            ->getDecodedBody();

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
    public function objectStoreService($name = null, $region = null, $urltype = null)
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
    public function computeService($name = null, $region = null, $urltype = null)
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
    public function orchestrationService($name = null, $region = null, $urltype = null)
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
