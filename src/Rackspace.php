<?php

namespace Rackspace;

use GuzzleHttp\Client;
use OpenStack\Common\Service\Builder;
use OpenStack\Common\Transport\HandlerStack;
use OpenStack\Common\Transport\Utils;
use Rackspace\Identity\v2\Service;

class Rackspace
{
    const US_AUTH = 'https://identity.api.rackspacecloud.com/v2.0';
    const UK_AUTH = 'https://lon.identity.api.rackspacecloud.com/v2.0';

    /** @var Builder */
    private $builder;

    /**
     * @param array $options User-defined options
     *
     * $options['username'] = (string) Your Rackspace username        [REQUIRED]
     *         ['apiKey']   = (string) Your Rackspace API key         [REQUIRED]
     *         ['debug']    = (bool)   Whether to enable HTTP logging [OPTIONAL]
     */
    public function __construct(array $options = [], Builder $builder = null)
    {
        if (!isset($options['authUrl'])) {
            $options['authUrl'] = self::US_AUTH;
        }

        $options['identityService'] = Service::factory(new Client([
            'base_uri' => Utils::normalizeUrl($options['authUrl']),
            'handler'  => HandlerStack::create(),
        ]));

        $this->builder = $builder ?: new Builder($options, __NAMESPACE__);
    }

    public function objectStoreV1(array $options = [])
    {
        $defaults = ['catalogName' => 'cloudFiles', 'catalogType' => 'object-store'];
        return $this->builder->createService('ObjectStore', 1, array_merge($defaults, $options));
    }

    public function objectStoreCdnV1(array $options = [])
    {
        $defaults = ['catalogName' => 'cloudFilesCDN', 'catalogType' => 'rax:object-cdn'];
        return $this->builder->createService('ObjectStoreCDN', 1, array_merge($defaults, $options));
    }

    /**
     * @param array $options
     *
     * @return \Rackspace\Compute\v2\Service
     */
    public function computeV2(array $options = [])
    {
        $defaults = ['catalogName' => 'cloudServersOpenStack', 'catalogType' => 'compute'];
        return $this->builder->createService('Compute', 2, array_merge($defaults, $options));
    }
}