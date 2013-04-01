<?php

namespace OpenCloud\ObjectStore;

/**
 * This is the CDN related to the ObjectStore
 *
 * This is intended for Rackspace customers, so it almost certainly will
 * not work for other public clouds.
 *
 * @param OpenCloud\OpenStack $conn a connection object
 * @param string $serviceName the name of the service to use
 * @param string $serviceRegion the name of the service region to use
 * @param string $urltype the type of URL to use (usually "publicURL")
 */
class ObjectStoreCDN extends ObjectStoreBase {

    /**
     * Creates a new ObjectStoreCDN object
     *
     * This is a simple wrapper function around the parent Service construct,
     * but supplies defaults for the service type.
     *
     * @param OpenStack $conn the connection object
     * @param string $serviceName the name of the service
     * @param string $serviceRegion the service's region
     * @param string $urlType the type of URL (normally 'publicURL')
     */
	public function __construct(
		\OpenCloud\OpenStack $conn,
		$serviceName=RAXSDK_OBJSTORE_NAME,
		$serviceRegion=RAXSDK_OBJSTORE_REGION,
		$urltype=RAXSDK_OBJSTORE_URLTYPE) {

		// call the parent contructor
		parent::__construct(
			$conn,
			'rax:object-cdn',
			$serviceName,
			$serviceRegion,
			$urltype
		);
	}

	/**
	 * Helps catch errors if someone calls the method on the
	 * wrong object
	 */
	public function CDN() {
	    throw new \OpenCloud\Base\Exceptions\CdnError(
	        \OpenCloud\Base\Lang::translate('Invalid method call; no CDN() on the CDN object'));
	}
}