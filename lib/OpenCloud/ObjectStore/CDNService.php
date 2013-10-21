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

/**
 * This is the CDN version of the ObjectStore service. 
 */
class CDNService extends AbstractService
{
    
    public function __construct(
        OpenStack $connection,
        $serviceName = RAXSDK_OBJSTORE_NAME,
        $serviceRegion = RAXSDK_OBJSTORE_REGION,
        $urltype = RAXSDK_URL_PUBLIC
    ) {
        $this->getLogger()->info('Initializing CDN Service...');
        
        parent::__construct(
            $connection,
            'rax:object-cdn',
            $serviceName,
            $serviceRegion,
            $urltype
        );
    }
    
}
