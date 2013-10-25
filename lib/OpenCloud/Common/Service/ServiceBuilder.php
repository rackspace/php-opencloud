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
        // Strip off base namespace
        $class = 'OpenCloud\\' . preg_replace('#\\\?OpenCloud\\\#', '', $class) . '\\Service';
        $defaults  = self::getDefaults($class);

        // @codeCoverageIgnoreStart
        if (!$name = !empty($options['name']) ? $options['name'] : $defaults['name']) {
            throw new ServiceException(sprintf(
                Lang::translate('No value for %s name'),
                $class
            ));
        }

        if (!$region = !empty($options['region']) ? $options['region'] : $defaults['region']) {
            throw new ServiceException(sprintf(
                Lang::translate('No value for %s region'),
                $class
            ));
        }

        if (!$urlType = !empty($options['urlType']) ? $options['urlType'] : $defaults['urlType']) {
            throw new ServiceException(sprintf(
                Lang::translate('No value for %s URL type'),
                $class
            ));
        }
        // @codeCoverageIgnoreEnd

        return new $class($client, $name, $region, $urlType);
    }

    /**
     * Get the default information from a Service class using its constants.
     *
     * @param $class
     * @return array
     */
    private static function getDefaults($class)
    {
        $base = __NAMESPACE__ . '\\AbstractService';
        
        return array(
            'name'    => (defined("{$class}::DEFAULT_NAME")) ? $class::DEFAULT_NAME : null,
            'region'  => (defined("{$class}::DEFAULT_REGION")) ? $class::DEFAULT_REGION : $base::DEFAULT_REGION,
            'urlType' => (defined("{$class}::DEFAULT_URL_TYPE")) ? $class::DEFAULT_URL_TYPE : $base::DEFAULT_URL_TYPE,
        );
    }
    
}