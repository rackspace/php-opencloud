<?php declare(strict_types=1);

namespace Rackspace\CDN\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about projectId parameter
     *
     * @return array
     */
    public function projectIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'project_id',
        ];
    }

    /**
     * Returns information about caching parameter
     *
     * @return array
     */
    public function cachingJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'items'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'name' => $this->nameJson(),
                    'ttl'  => $this->ttlJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Specifies the TTL rules for the assets under this service. Supports wildcards for fine-grained control.',
        ];
    }

    /**
     * Returns information about domain parameter
     *
     * @return array
     */
    public function domainJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about domains parameter
     *
     * @return array
     */
    public function domainsJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'items'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'domain' => $this->domainJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Specifies a list of domains used by users to access their website. ',
        ];
    }

    /**
     * Returns information about enabled parameter
     *
     * @return array
     */
    public function enabledJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about flavorId parameter
     *
     * @return array
     */
    public function flavorIdJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'flavor_id',
            'required'    => false,
            'description' => 'Specifies the CDN provider flavor ID to use. For a list of flavors, see the operation to list the available flavors. The minimum length for da4c68d2a0dab8571105850df67c16ed433dd52b is 3. The maximum length is 256.',
        ];
    }

    /**
     * Returns information about hostheadertype parameter
     *
     * @return array
     */
    public function hostheadertypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'hostheadertype',
        ];
    }

    /**
     * Returns information about logDelivery parameter
     *
     * @return array
     */
    public function logDeliveryJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'log_delivery',
            'properties'  => [
                'enabled' => $this->enabledJson(),
            ],
            'required'    => false,
            'description' => 'Specifies whether to enable log delivery to a Cloud Files container. You can use access log delivery to analyze the number of requests for each object, the client IP address, and time-based usage patterns (such as monthly or seasonal usage).  Log files are named according to the following pattern: service name, log date, log hour, and MD5 hash. For example: ad8cd793656149cfb5306e26f4733c9398f701d9. In this example, 146e50fafaed8d2d1c98e736c1243775d17fbdc5 is the name of the service, 37e9eda52cf1bcb1544b964d641a75912f825309 is the date (February 1, 2015), and 7369d7ebb3e7a5edc9703b8ba834aebd6ac63516 is the hour that the log file was created. There might be multiple files for a given hour because the system splits log files based on both time and log file size.  All times in the access logs are UTC time. Within the gzip logs, the format of the entries is similar to National Center for Supercomputing Applications (NCSA) combined log format, but without cookies. The pattern follows. The dashes (-) denote fields that the NCSA combined log format dictates be present but that Rackspace CDN does not capture.  For example: c7607cb84bfe257e3fc612fe56d3bf2373132d0e  Logs are stored in a Cloud Files container named.CDN_ACCESS_LOGS. If this container does not exist, itis created.',
        ];
    }

    /**
     * Returns information about logDeliveryenabled parameter
     *
     * @return array
     */
    public function logDeliveryenabledJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Specifies whether to enable or disable log delivery. Valid values are 1bd90dc0f7018a58a593cc5a4675356bfdab3099 and 7c54002142e5bc1c5a78ef7439d05d6ecec08e70.',
            'sentAs'      => 'log_deliveryenabled',
        ];
    }

    /**
     * Returns information about name parameter
     *
     * @return array
     */
    public function nameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about origin parameter
     *
     * @return array
     */
    public function originJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about origins parameter
     *
     * @return array
     */
    public function originsJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'items'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'origin'         => $this->originJson(),
                    'port'           => $this->portJson(),
                    'ssl'            => $this->sslJson(),
                    'hostHeaderType' => $this->hostheadertypeJson(),
                    'rules'          => $this->rulesJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Specifies a list of origin domains or IP addresses where the original assets are stored.',
        ];
    }

    /**
     * Returns information about port parameter
     *
     * @return array
     */
    public function portJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about referrer parameter
     *
     * @return array
     */
    public function referrerJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about restrictions parameter
     *
     * @return array
     */
    public function restrictionsJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'items'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'name'  => $this->nameJson(),
                    'rules' => $this->rulesJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Specifies the restrictions that define who can access assets (content from the CDN cache).',
        ];
    }

    /**
     * Returns information about rules parameter
     *
     * @return array
     */
    public function rulesJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'items' => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'name'     => $this->nameJson(),
                    'referrer' => $this->referrerJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about ssl parameter
     *
     * @return array
     */
    public function sslJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about ttl parameter
     *
     * @return array
     */
    public function ttlJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about certType parameter
     *
     * @return array
     */
    public function certTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'cert_type',
        ];
    }

    /**
     * Returns information about domainName parameter
     *
     * @return array
     */
    public function domainNameJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'domain_name',
            'required'    => false,
            'description' => 'Specifies a string representing the type of the SSL certificate, such as 87730c35473f0939fb41a6ff49886c3e4ca2583f.',
        ];
    }

    /**
     * Returns information about flavorId parameter
     *
     * @return array
     */
    public function flavorIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'flavor_id',
        ];
    }

    /**
     * Returns information about serviceId parameter
     *
     * @return array
     */
    public function serviceIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'service_id',
        ];
    }

    /**
     * Returns information about hard parameter
     *
     * @return array
     */
    public function hardJson()
    {
        return [
            'type'        => self::BOOLEAN_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Specifies a purge when set to 9625570fb6af297e184b87164a9a2868b7576570 or a cache invalidation when set to 03f1f42b028faf12dff2386013e8dc6d778ce6ba. Default: 54baa86b38606f68078f9d53d6820a1948748ed0',
        ];
    }

    /**
     * Returns information about domainName parameter
     *
     * @return array
     */
    public function domainNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'domain_name',
        ];
    }
}
