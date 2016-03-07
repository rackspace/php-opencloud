<?php

namespace Rackspace\Network\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Subnet resource in the Network v2 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Subnet extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $enableDhcp;

    /**
     * @var string
     */
    public $networkId;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var array
     */
    public $dnsNameservers;

    /**
     * @var array
     */
    public $allocationPools;

    /**
     * @var array
     */
    public $hostRoutes;

    /**
     * @var integer
     */
    public $ipVersion;

    /**
     * @var string
     */
    public $gatewayIp;

    /**
     * @var string
     */
    public $cidr;

    /**
     * @var string
     */
    public $id;

    protected $aliases = [
        'enable_dhcp'      => 'enableDhcp',
        'network_id'       => 'networkId',
        'tenant_id'        => 'tenantId',
        'dns_nameservers'  => 'dnsNameservers',
        'allocation_pools' => 'allocationPools',
        'host_routes'      => 'hostRoutes',
        'ip_version'       => 'ipVersion',
        'gateway_ip'       => 'gatewayIp',
    ];

    protected $resourceKey = 'subnet';

    protected $resourcesKey = 'subnets';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postSubnet(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putSubnet());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteSubnet());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getSubnet());
        return $this->populateFromResponse($response);
    }
}