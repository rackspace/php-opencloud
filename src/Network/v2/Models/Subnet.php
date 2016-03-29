<?php declare(strict_types=1);

namespace Rackspace\Network\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

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
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postSubnets(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putSubnetId());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteSubnetId());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getSubnetId());
        $this->populateFromResponse($response);
    }
}