<?php declare(strict_types=1);

namespace Rackspace\RackConnect\v3;

use OpenCloud\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET /v3/public_ips HTTP operation
     *
     * @return array
     */
    public function getPublicIps()
    {
        return [
            'method' => 'GET',
            'path'   => 'public_ips',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /v3/public_ips HTTP operation
     *
     * @return array
     */
    public function postPublicIps()
    {
        return [
            'method'  => 'POST',
            'path'    => '/v3/public_ips',
            'jsonKey' => 'cloud_server',
            'params'  => [
                'id' => $this->params->idJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /v3/public_ips/{public_IP_id} HTTP operation
     *
     * @return array
     */
    public function getPublicIPId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v3/public_ips/{public_IP_id}',
            'params' => [
                'publicIPId' => $this->params->publicIPIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v3/public_ips/{public_IP_id} HTTP operation
     *
     * @return array
     */
    public function deletePublicIPId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v3/public_ips/{public_IP_id}',
            'params' => [
                'publicIPId' => $this->params->publicIPIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET cloud_networks HTTP operation
     *
     * @return array
     */
    public function getCloudNetworks()
    {
        return [
            'method' => 'GET',
            'path'   => 'cloud_networks',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /v3/public_ips/cloud_server_id HTTP operation
     *
     * @return array
     */
    public function getCloudServerId()
    {
        return [
            'method' => 'GET',
            'path'   => 'public_ips/cloud_server_id',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET load_balancer_pools HTTP operation
     *
     * @return array
     */
    public function getLoadBalancerPools()
    {
        return [
            'method' => 'GET',
            'path'   => 'load_balancer_pools',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST load_balancer_pools/nodes HTTP
     * operation
     *
     * @return array
     */
    public function postNodes()
    {
        return [
            'method'  => 'POST',
            'path'    => 'load_balancer_pools/nodes',
            'jsonKey' => 'cloud_server',
            'params'  => [
                'id' => $this->params->idJson(),
            ],
        ];
    }

    /**
     * Returns information about GET load_balancer_pools/nodes HTTP
     * operation
     *
     * @return array
     */
    public function getNodes()
    {
        return [
            'method' => 'GET',
            'path'   => 'load_balancer_pools/nodes',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET cloud_networks/{network_id} HTTP
     * operation
     *
     * @return array
     */
    public function getNetworkId()
    {
        return [
            'method' => 'GET',
            'path'   => 'cloud_networks/{network_id}',
            'params' => [
                'networkId' => $this->params->networkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET load_balancer_pools/details HTTP
     * operation
     *
     * @return array
     */
    public function getDetails()
    {
        return [
            'method' => 'GET',
            'path'   => 'load_balancer_pools/details',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET
     * load_balancer_pools/{load_balancer_pool_id} HTTP operation
     *
     * @return array
     */
    public function getLoadBalancerPoolId()
    {
        return [
            'method' => 'GET',
            'path'   => 'load_balancer_pools/{load_balancer_pool_id}',
            'params' => [
                'loadBalancerPoolId' => $this->params->loadBalancerPoolIdUrl(),
            ],
        ];
    }
}