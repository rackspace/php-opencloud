<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

use OpenCloud\Common\Constants\Header;
use OpenCloud\Common\Constants\Mime;
use OpenCloud\Common\Http\Client;
use OpenCloud\Common\Http\Message\RequestSubscriber;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Service\ServiceBuilder;
use OpenCloud\Common\Service\Catalog;
use OpenCloud\Common\Http\Message\Formatter;
use Guzzle\Http\Url;

define('RACKSPACE_US', 'https://identity.api.rackspacecloud.com/v2.0/');
define('RACKSPACE_UK', 'https://lon.identity.api.rackspacecloud.com/v2.0/');

/**
 * The main client of the library. This object is the central point of negotiation between your application and the
 * API because it handles all of the HTTP transactions required to perform operations. It also manages the services
 * for your application through convenient factory methods.
 */
class OpenStack extends Client
{
    /**
     * @var array Credentials passed in by the user
     */
    private $secret = array();

    /**
     * @var string The token produced by the API
     */
    private $token;

    /**
     * @var int The expiration date (in Unix time) for the current token
     */
    private $expiration;

    /**
     * @var string The unique identifier for who's accessing the API
     */
    private $tenant;

    /**
     * @var \OpenCloud\Common\Service\Catalog The catalog of services which are provided by the API
     */
    private $catalog;

    /**
     * @var \OpenCloud\Common\Log\LoggerInterface The object responsible for logging output
     */
    private $logger;

    /**
     * @var string The endpoint URL used for authentication
     */
    private $authUrl;

    public function __construct($url, array $secret, array $options = array())
    {
        $this->getLogger()->info(Lang::translate('Initializing OpenStack client'));

        $this->setSecret($secret);
        $this->setAuthUrl($url);

        parent::__construct($url, $options);
        
        $this->addSubscriber(RequestSubscriber::getInstance());
        $this->setDefaultOption('headers/Accept', 'application/json');
    }
        
    /**
     * Set the credentials for the client
     *
     * @param array $secret
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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

    /**
     * @param Common\Log\LoggerInterface $logger
     * @return $this
     */
    public function setLogger(Common\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return Common\Log\LoggerInterface
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->setLogger(new Common\Log\Logger);
        }
        return $this->logger;
    }
    
    /**
     * Checks whether token has expired
     * 
     * @return bool
     */
    public function hasExpired()
    {
        return !$this->expiration || (time() > $this->expiration);
    }

    /**
     * Formats the credentials array (as a string) for authentication
     *
     * @return string
     * @throws Common\Exceptions\CredentialError
     */
    public function getCredentials()
    {
        if (!empty($this->secret['username']) && !empty($this->secret['password'])) {

            $credentials = array('auth' => array(
                'passwordCredentials' => array(
                    'username' => $this->secret['username'],
                    'password' => $this->secret['password']
                )
            ));

            if (!empty($this->secret['tenantName'])) {
                $credentials['auth']['tenantName'] = $this->secret['tenantName'];
            } elseif (!empty($this->secret['tenantId'])) {
                $credentials['auth']['tenantId'] = $this->secret['tenantId'];
            }

            return json_encode($credentials);

        } else {
            throw new Exceptions\CredentialError(
               Lang::translate('Unrecognized credential secret')
            );
        }
    }

    /**
     * @param $url
     * @return $this
     */
    public function setAuthUrl($url)
    {
	    $this->authUrl = $url;
	    return $this;
    }

    /**
     * @return Url
     */
    public function getAuthUrl()
    {
	    return Url::factory($this->authUrl)->addPath('tokens');
    }

    /**
     * Authenticate the tenant using the supplied credentials
     *
     * @return void
     * @throws AuthenticationError
     */
    public function authenticate()
    {
        $headers = array(Header::CONTENT_TYPE => Mime::JSON);
        $response = $this->post($this->getAuthUrl('tokens'), $headers, $this->getCredentials())->send();
        $body = Formatter::decode($response);

        // Save the token information as well as the ServiceCatalog
        $this->setToken($body->access->token->id);
        $this->setExpiration(strtotime($body->access->token->expires));
        $this->setCatalog($body->access->serviceCatalog);
        
        // Add to default headers for future reference
        $this->setDefaultOption('headers/X-Auth-Token', (string) $this->getToken());

        /**
         * In some cases, the tenant name/id is not returned
         * as part of the auth token, so we check for it before
         * we set it. This occurs with pure Keystone, but not
         * with the Rackspace auth.
         */
        if (isset($body->access->token->tenant)) {
            $this->setTenant($body->access->token->tenant->id);
        }
    }

    /**
     * @deprecated
     */
    public function getUrl()
    {
        return $this->getBaseUrl();
    }

    /**
     * Convenience method for exporting current credentials. Useful for local caching.
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
     * Convenience method for importing credentials. Useful for local caching because it reduces HTTP traffic.
     *
     * @param array $values
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

    /**
     * Creates a new ObjectStore object (Swift/Cloud Files)
     *
     * @param string $name    The name of the service as it appears in the Catalog
     * @param string $region  The region (DFW, IAD, ORD, LON, SYD)
     * @param string $urltype The URL type ("publicURL" or "internalURL")
     * @return \OpenCloud\ObjectStore\Service
     */
    public function objectStoreService($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'OpenCloud\ObjectStore\Service', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

    /**
     * Creates a new Compute object (Nova/Cloud Servers)
     *
     * @param string $name    The name of the service as it appears in the Catalog
     * @param string $region  The region (DFW, IAD, ORD, LON, SYD)
     * @param string $urltype The URL type ("publicURL" or "internalURL")
     * @return \OpenCloud\Compute\Service
     */
    public function computeService($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'OpenCloud\Compute\Service', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

    /**
     * Creates a new Orchestration (Heat) service object
     *
     * @param string $name    The name of the service as it appears in the Catalog
     * @param string $region  The region (DFW, IAD, ORD, LON, SYD)
     * @param string $urltype The URL type ("publicURL" or "internalURL")
     * @return \OpenCloud\Orchestration\Service
     * @codeCoverageIgnore
     */
    public function orchestrationService($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'OpenCloud\Orchestration\Service', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

    /**
     * Creates a new Volume (Cinder) service object
     *
     * @param string $name    The name of the service as it appears in the Catalog
     * @param string $region  The region (DFW, IAD, ORD, LON, SYD)
     * @param string $urltype The URL type ("publicURL" or "internalURL")
     * @return \OpenCloud\Volume\Service
     */
    public function volumeService($name = null, $region = null, $urltype = null)
    {
        return ServiceBuilder::factory($this, 'OpenCloud\Volume\Service', array(
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));
    }

}
