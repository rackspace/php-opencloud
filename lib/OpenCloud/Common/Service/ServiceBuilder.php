<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Service;

use OpenCloud\Common\Http\Client;
use OpenCloud\Common\Exceptions\ServiceException;

/**
 * This object is a factory for building Service objects.
 */
class ServiceBuilder
{

    /**
     * Simple factory method for creating services.
     * 
     * @param Client $client The HTTP client object
     * @param string $class  The class name of the service
     * @param array $options The options.
     * @return \OpenCloud\Common\Service\AbstractService
     * @throws ServiceException
     */
    public static function factory(Client $client, $class, array $options = array())
    {
        $name = isset($options['name']) ? $options['name'] : null;
        $region = isset($options['region']) ? $options['region'] : null;
        $urlType = isset($options['urlType']) ? $options['urlType'] : null;

        return new $class($client, null, $name, $region, $urlType);
    }
    
}