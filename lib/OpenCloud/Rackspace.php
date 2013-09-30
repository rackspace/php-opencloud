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

/**
 * Rackspace extends the OpenStack class with support for Rackspace's
 * API key and tenant requirements.
 *
 * The only difference between Rackspace and OpenStack is that the
 * Rackspace class generates credentials using the username
 * and API key, as required by the Rackspace authentication
 * service.
 *
 * Example:
 * <code>
 * $conn = new Rackspace(
 *      'https://identity.api.rackspacecloud.com/v2.0/',
 *      array(
 *          'username' => 'FRED',
 *          'apiKey'   => '0900af093093788912388fc09dde090ffee09'
 *      )
 * );
 * </code>
 */
class Rackspace extends OpenStack
{

    /**
     * JSON template for Rackspace credentials
     */
    const CREDS_TEMPLATE = <<<EOF
{"auth":{ "RAX-KSKEY:apiKeyCredentials": { "username": "%s", "apiKey": "%s" } }}
EOF;

    /**
     * Generates Rackspace API key credentials
     *
     * @return string
     */
    public function credentials()
    {
        $secret = $this->secret();
        
        return (isset($secret['username']) && isset($secret['apiKey']))
            ? sprintf(self::CREDS_TEMPLATE, $secret['username'], $secret['apiKey'])
            : parent::credentials();
    }

    /**
     * Creates a new Database service. Note: this is a Rackspace-only feature.
     * 
     * @param  string $name
     * @param  string $region
     * @param  string $urltype
     * @return OpenCloud\Queues\Service
     */
    public function dbService($name = null, $region = null, $urltype = null)
    {
        return $this->Service('Database', $name, $region, $urltype);
    }

    /**
     * Creates a new Load Balancer service. Note: this is a Rackspace-only feature.
     * 
     * @param  string $name
     * @param  string $region
     * @param  string $urltype
     * @return OpenCloud\Queues\Service
     */
    public function loadBalancerService($name = null, $region = null, $urltype = null)
    {
        return $this->Service('LoadBalancer', $name, $region, $urltype);
    }

    /**
     * Creates a new DNS service. Note: this is a Rackspace-only feature.
     * 
     * @param  string $name
     * @param  string $region
     * @param  string $urltype
     * @return OpenCloud\Queues\Service
     */
    public function DNS($name = null, $region = null, $urltype = null)
    {
        return $this->Service('DNS', $name, $region, $urltype);
    }

    /**
     * Creates a new Monitoring service. Note: this is a Rackspace-only feature.
     * 
     * @param  string $name
     * @param  string $region
     * @param  string $urltype
     * @return OpenCloud\Queues\Service
     */
    public function cloudMonitoring($name = null, $region = null, $urltype = null)
    {
        return $this->Service('CloudMonitoring', $name, $region, $urltype);
    }

    /**
     * Creates a new CloudQueues service. Note: this is a Rackspace-only feature.
     * 
     * @param  string $name
     * @param  string $region
     * @param  string $urltype
     * @return OpenCloud\Queues\Service
     */
    public function autoscale($name = null, $region = null, $urltype = null)
    {
        return $this->service('Autoscale', $name, $region, $urltype);
    }
    
    /**
     * Creates a new CloudQueues service. Note: this is a Rackspace-only feature.
     * 
     * @param  string $name
     * @param  string $region
     * @param  string $urltype
     * @return OpenCloud\Queues\Service
     */
    public function queues($name = null, $region = null, $urltype = null)
    {
        return $this->service('Queues', $name, $region, $urltype);
    }
    
}
