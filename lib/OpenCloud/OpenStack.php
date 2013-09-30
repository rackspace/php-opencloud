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
use OpenCloud\Common\Http\Message\RequestFactory;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\ServiceCatalogItem;

class OpenStack extends Client
{
    const VERSION = '1.7.0';
    
    private $secret = array();
    private $token;
    private $expiration = 0;
    private $tenant;
    private $catalog;
    private $logger;
    
    private $exportItems = array(
        'token',
        'expiration',
        'tenant',
        'catalog'
    );

    public function __construct($url, array $secret, array $options = array())
    {
        $this->getLogger()->info(Lang::translate('Initializing OpenStack client'));
        
        $this->setSecret($secret);
        $this->setRequestFactory(RequestFactory::getInstance());
        
        parent::__construct($url, $options);
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
        if (null === $this->token || $this->hasExpired()) {
            $this->authenticate();
        } 
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
        if (null === $this->expiration || $this->hasExpired()) {
            $this->authenticate();
        }
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
        if (null === $this->tenant || $this->hasExpired()) {
            $this->authenticate();
        }
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
        $this->catalog = $catalog;
        
        return $this;
    }
    
    /**
     * Get the service catalog.
     * 
     * @return array
     */
    public function getCatalog()
    {
        if (null === $this->catalog || $this->hasExpired()) {
            $this->authenticate();
        }
        return $this->catalog;
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
     * Returns the stored secret
     *
     * @return array
     */
    public function secret()
    {
        return $this->getSecret();
    }
    
    /**
     * Checks whether token has expired.
     * 
     * @return bool
     */
    public function hasExpired()
    {
        return time() > ($this->expiration - RAXSDK_FUDGE);
    }
    
    /**
     * Returns the cached token; if it has expired, then it re-authenticates
     *
     * @api
     * @return string
     */
    public function token()
    {
        return $this->getToken();
    }

    /**
     * Returns the cached expiration time;
     * if it has expired, then it re-authenticates
     *
     * @api
     * @return string
     */
    public function expiration()
    {
        return $this->getExpiration();
    }

    /**
     * Returns the tenant ID, re-authenticating if necessary
     *
     * @api
     * @return string
     */
    public function tenant()
    {
        return $this->getTenant();
    }

    /**
     * Returns the service catalog object from the auth service
     *
     * @return \stdClass
     */
    public function serviceCatalog()
    {
        return $this->getCatalog();
    }

    /**
     * Returns a Collection of objects with information on services
     *
     * Note that these are informational (read-only) and are not actually
     * 'Service'-class objects.
     */
    public function serviceList()
    {
        return new Common\Collection($this, 'ServiceCatalogItem', $this->serviceCatalog());
    }

    /**
     * Creates and returns the formatted credentials to POST to the auth
     * service.
     *
     * @return string
     */
    public function credentials()
    {
        if (isset($this->secret['username']) && isset($this->secret['password'])) {
            
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
        // try to auth
        $response = $this->post('tokens', array(), $this->credentials())->send();
        $object = $response->getDecodedBody();

        // Save the token information as well as the ServiceCatalog
        $this->setToken($object->access->token->id);
        $this->setExpiration(strtotime($object->access->token->expires));
        $this->setCatalog($object->access->serviceCatalog);

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
    	$this->authenticate();
    	
        $array = array();
        
        foreach ($this->getExportItems() as $key) {
            $array[$key] = $this->$key;
        }
        
        return $array;
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
        foreach ($this->getExportItems() as $item) {
            $this->$item = $values[$item];
        }
    }

    /********** FACTORY METHODS **********
     * 
     * These methods are provided to permit easy creation of services
     * (for example, Nova or Swift) from a connection object. As new
     * services are supported, factory methods should be provided here.
     */

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
        return $this->service('ObjectStore', $name, $region, $urltype);
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
        return $this->service('Compute', $name, $region, $urltype);
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
        return $this->service('Orchestration', $name, $region, $urltype);
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
        return $this->service('Volume', $name, $region, $urltype);
    }

    /**
     * Generic Service factory method
     *
     * Contains code reused by the other service factory methods.
     *
     * @param string $class the name of the Service class to produce
     * @param string $name the name of the Compute service to attach to
     * @param string $region the name of the region to use
     * @param string $urltype the URL type (normally "publicURL")
     * @return Service (or subclass such as Compute, ObjectStore)
     * @throws ServiceValueError
     */
    public function service($class, $name = null, $region = null, $urltype = null)
    {
        // debug message
        $this->getLogger()->info('Factory for class [{class}] [{name}/{region}/{urlType}]', array(
            'class'   => $class, 
            'name'    => $name, 
            'region'  => $region, 
            'urlType' => $urltype
        ));

        // Strips off base namespace 
        $class = preg_replace('#\\\?OpenCloud\\\#', '', $class);

        // check for defaults
        $fullclass = 'OpenCloud\\' . $class . '\\Service';
        $default   = $this->getDefaults($fullclass);

        // report errors
        if (!$name = $name ?: $default['name']) {
            throw new Exceptions\ServiceValueError(sprintf(
                Lang::translate('No value for %s name'),
                $class
            ));
        }

        if (!$region = $region ?: $default['region']) {
            throw new Exceptions\ServiceValueError(sprintf(
                Lang::translate('No value for %s region'),
                $class
            ));
        }

        if (!$urltype = $urltype ?: $default['urlType']) {
            throw new Exceptions\ServiceValueError(sprintf(
                Lang::translate('No value for %s URL type'),
                $class
            ));
        }

        return new $fullclass($this, $name, $region, $urltype);
    }
    
    private function getDefaults($class)
    {
        $base = 'OpenCloud\\Common\\Service';
        
        return array(
            'name'    => (defined("{$class}::DEFAULT_NAME")) ? $class::DEFAULT_NAME : $base::DEFAULT_NAME,
            'region'  => (defined("{$class}::DEFAULT_REGION")) ? $class::DEFAULT_REGION : $base::DEFAULT_REGION,
            'urlType' => (defined("{$class}::DEFAULT_URL_TYPE")) ? $class::DEFAULT_URL_TYPE : $base::DEFAULT_URL_TYPE,
        );
    }
    
    /**
     * returns a service catalog item
     *
     * This is a helper function used to list service catalog items easily
     */
    public function serviceCatalogItem($info = array())
    {
        return new ServiceCatalogItem($info);
    }
    
    public function setLogger(Common\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Returns the Logger object.
     * 
     * @return \OpenCloud\Common\Log\AbstractLogger
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->setLogger(new Common\Log\Logger);
        }
        return $this->logger;
    }
    
    public function url()
    {
        return $this->getBaseUrl();
    }
    
}
