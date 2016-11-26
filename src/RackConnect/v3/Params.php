<?php declare(strict_types=1);

namespace Rackspace\RackConnect\v3;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about id parameter
     *
     * @return array
     */
    public function idJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about publicIPId parameter
     *
     * @return array
     */
    public function publicIPIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'public_IP_id',
        ];
    }

    /**
     * Returns information about tenantId parameter
     *
     * @return array
     */
    public function tenantIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'tenant_id',
        ];
    }

    /**
     * Returns information about networkId parameter
     *
     * @return array
     */
    public function networkIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'network_id',
        ];
    }

    /**
     * Returns information about loadBalancerPoolId parameter
     *
     * @return array
     */
    public function loadBalancerPoolIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'load_balancer_pool_id',
        ];
    }
}
