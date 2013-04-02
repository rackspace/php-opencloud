<?php

namespace OpenCloud\ObjectStore;

use OpenCloud\Base\Lang;

/**
 * ObjectStore - this defines the object-store (Cloud Files) service.
 *
 * Usage:
 * <code>
 *      $conn = new OpenStack('{URL}', '{SECRET}');
 *      $ostore = new OpenCloud\ObjectStore(
 *          $conn,
 *          'service name',
 *          'service region',
 *          'URL type'
 *      );
 * </code>
 *
 * Default values for service name, service region, and urltype can be
 * provided via the global constants RAXSDK_OBJSTORE_NAME,
 * RAXSDK_OBJSTORE_REGION, and RAXSDK_OBJSTORE_URLTYPE.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

class Service extends ObjectStoreBase {
	
    /**
     * This holds the associated CDN object (for Rackspace public cloud)
     * or is NULL otherwise. The existence of an object here is
     * indicative that the CDN service is available.
     */
	private $cdn;

    /**
     * creates a new ObjectStore object
     *
     * @param OpenCloud\OpenStack $conn a connection object
     * @param string $serviceName the name of the service to use
     * @param string $serviceRegion the name of the service region to use
     * @param string $urltype the type of URL to use (usually "publicURL")
     */
	public function __construct(
		\OpenCloud\OpenStack $conn,
		$serviceName=RAXSDK_OBJSTORE_NAME,
		$serviceRegion=RAXSDK_OBJSTORE_REGION,
		$urltype=RAXSDK_OBJSTORE_URLTYPE) {
		$this->debug(Lang::translate('initializing ObjectStore...'));

		// call the parent contructor
		parent::__construct(
			$conn,
			'object-store',
			$serviceName,
			$serviceRegion,
			$urltype
		);

		// establish the CDN container, if available
		try {
            $this->cdn = new ObjectStoreCDN(
                $conn,
                $serviceName.'CDN', // will work for Rackspace
                $serviceRegion,
                $urltype
            );
		} catch (\OpenCloud\Base\Exceptions\EndpointError $e) {
		    /**
		     * if we have an endpoint error, then
		     * the CDN functionality is not available
		     * In this case, we silently ignore  it.
		     */
		    $this->cdn = NULL;
		}
	} // function __construct()

	/**
	 * sets the shared secret value for the TEMP_URL
	 *
	 * @param string $secret the shared secret
	 * @return HttpResponse
	 */
	public function SetTempUrlSecret($secret) {
		$resp = $this->Request($this->Url(), 'POST',
			array('X-Account-Meta-Temp-Url-Key' => $secret));
		if ($resp->HttpStatus() > 204)
			throw new \OpenCloud\Base\Exceptions\HttpError(sprintf(
				Lang::translate('Error in request, status [%d] for URL [%s] [%s]'),
				$resp->HttpStatus(),
				$this->Url(),
				$resp->HttpBody()));
		return $resp;
	}

    /**
     * returns the CDN object
     */
    public function CDN() {
        return $this->cdn;
    }
}
