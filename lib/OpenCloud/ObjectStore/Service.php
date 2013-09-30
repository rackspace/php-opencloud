<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions;

/**
 * The ObjectStore (Cloud Files) service.
 */
class Service extends AbstractService 
{
    
    const DEFAULT_NAME = 'cloudFiles';
    
    /**
     * This holds the associated CDN service (for Rackspace public cloud)
     * or is NULL otherwise. The existence of an object here is
     * indicative that the CDN service is available.
     */
    private $cdn;

    /**
     * Creates a new ObjectStore service object.
     *
     * @param OpenCloud\OpenStack $connection    The connection object
     * @param string              $serviceName   The name of the service
     * @param string              $serviceRegion The service's region
     * @param string              $urlType       The type of URL (normally 'publicURL')
     */
    public function __construct(
        OpenStack $connection,
        $serviceName = RAXSDK_OBJSTORE_NAME,
        $serviceRegion = RAXSDK_OBJSTORE_REGION,
        $urltype = RAXSDK_OBJSTORE_URLTYPE
    ) {
        $this->getLogger()->info('Initializing Container Service...');

        parent::__construct(
            $connection,
            'object-store',
            $serviceName,
            $serviceRegion,
            $urltype
        );

        // establish the CDN container, if available
        try {
            $this->cdn = new CDNService(
                $connection,
                $serviceName . 'CDN',
                $serviceRegion,
                $urltype
            );
        } catch (Exceptions\EndpointError $e) {
             // If we have an endpoint error, then the CDN functionality is not 
             // available. In this case, we silently ignore  it.
        }
    }

    /** 
     * Sets the shared secret value for the TEMP_URL
     *
     * @param string $secret the shared secret
     * @return HttpResponse
     */
    public function setTempUrlSecret($secret) 
    {
        return $this->getClient()->post($this->url(), array(
            'X-Account-Meta-Temp-Url-Key' => $secret
        ));
    }

    /**
     * Get the CDN service.
     * 
     * @return null|CDNService
     */
    public function getCDNService() 
    {
        return $this->cdn;
    }
    
    /**
     * Backwards compability.
     */
    public function CDN()
    {
        return $this->getCDNService();
    }
    
}
