<?php declare(strict_types=1);

namespace Rackspace\Network\v2;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET ports HTTP operation
     *
     * @return array
     */
    public function getPorts()
    {
        return [
            'method' => 'GET',
            'path'   => 'ports',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST ports HTTP operation
     *
     * @return array
     */
    public function postPorts()
    {
        return [
            'method'  => 'POST',
            'path'    => 'ports',
            'jsonKey' => 'port',
            'params'  => [
                'adminStateUp' => $this->params->adminStateUpJson(),
                'deviceId'     => $this->params->deviceIdJson(),
                'name'         => $this->params->nameJson(),
                'networkId'    => $this->params->networkIdJson(),
            ],
        ];
    }

    /**
     * Returns information about GET subnets HTTP operation
     *
     * @return array
     */
    public function getSubnets()
    {
        return [
            'method' => 'GET',
            'path'   => 'subnets',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST subnets HTTP operation
     *
     * @return array
     */
    public function postSubnets()
    {
        return [
            'method'  => 'POST',
            'path'    => 'subnets',
            'jsonKey' => 'subnet',
            'params'  => [
                'allocationPools' => $this->params->allocationPoolsJson(),
                'cidr'            => $this->params->cidrJson(),
                'destination'     => $this->params->destinationJson(),
                'end'             => $this->params->endJson(),
                'gatewayIp'       => $this->params->gatewayIpJson(),
                'hostRoutes'      => $this->params->hostRoutesJson(),
                'ipVersion'       => $this->params->ipVersionJson(),
                'name'            => $this->params->nameJson(),
                'networkId'       => $this->params->networkIdJson(),
                'nexthop'         => $this->params->nexthopJson(),
                'start'           => $this->params->startJson(),
                'tenantId'        => $this->params->tenantIdJson(),
            ],
        ];
    }

    /**
     * Returns information about POST networks HTTP operation
     *
     * @return array
     */
    public function postNetworks()
    {
        return [
            'method'  => 'POST',
            'path'    => 'networks',
            'jsonKey' => 'network',
            'params'  => [
                'name'   => $this->params->nameJson(),
                'shared' => $this->params->sharedJson(),
            ],
        ];
    }

    /**
     * Returns information about GET networks HTTP operation
     *
     * @return array
     */
    public function getNetworks()
    {
        return [
            'method' => 'GET',
            'path'   => 'networks',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET ip_addresses HTTP operation
     *
     * @return array
     */
    public function getIpAddresses()
    {
        return [
            'method' => 'GET',
            'path'   => 'ip_addresses',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST ip_addresses HTTP operation
     *
     * @return array
     */
    public function postIpAddresses()
    {
        return [
            'method'  => 'POST',
            'path'    => 'ip_addresses',
            'jsonKey' => 'ip_address',
            'params'  => [
                'id'       => $this->params->networkIdJson(),
                'portIds'  => $this->params->portIdsJson(),
                'tenantId' => $this->params->tenantIdJson(),
                'version'  => $this->params->versionJson(),
            ],
        ];
    }

    /**
     * Returns information about POST security-groups HTTP operation
     *
     * @return array
     */
    public function postSecuritygroups()
    {
        return [
            'method'  => 'POST',
            'path'    => 'security-groups',
            'jsonKey' => 'security_group',
            'params'  => [
                'description' => $this->params->descriptionJson(),
                'name'        => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET ports/{id} HTTP operation
     *
     * @return array
     */
    public function getPortId()
    {
        return [
            'method' => 'GET',
            'path'   => 'ports/{id}',
            'params' => [
                'id' => $this->params->portIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT ports/{id} HTTP operation
     *
     * @return array
     */
    public function putPortId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'ports/{id}',
            'jsonKey' => 'port',
            'params'  => [
                'id'           => $this->params->portIdUrl(),
                'adminStateUp' => $this->params->adminStateUpJson(),
                'deviceId'     => $this->params->deviceIdJson(),
                'name'         => $this->params->nameJson(),
                'networkId'    => $this->params->networkIdJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE ports/{id} HTTP operation
     *
     * @return array
     */
    public function deletePortId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'ports/{id}',
            'params' => [
                'id' => $this->params->portIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET security-groups HTTP operation
     *
     * @return array
     */
    public function getSecuritygroups()
    {
        return [
            'method' => 'GET',
            'path'   => 'security-groups',
            'params' => [],
        ];
    }

    /**
     * Returns information about DELETE subnets/{id} HTTP operation
     *
     * @return array
     */
    public function deleteSubnetId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'subnets/{id}',
            'params' => [
                'id' => $this->params->subnetIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET subnets/{id} HTTP operation
     *
     * @return array
     */
    public function getSubnetId()
    {
        return [
            'method' => 'GET',
            'path'   => 'subnets/{id}',
            'params' => [
                'id' => $this->params->subnetIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT subnets/{id} HTTP operation
     *
     * @return array
     */
    public function putSubnetId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'subnets/{id}',
            'jsonKey' => 'subnet',
            'params'  => [
                'id'   => $this->params->subnetIdUrl(),
                'name' => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET security-group-rules HTTP operation
     *
     * @return array
     */
    public function getSecuritygrouprules()
    {
        return [
            'method' => 'GET',
            'path'   => 'security-group-rules',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST security-group-rules HTTP operation
     *
     * @return array
     */
    public function postSecuritygrouprules()
    {
        return [
            'method' => 'POST',
            'path'   => 'security-group-rules',
            'params' => [],
        ];
    }

    /**
     * Returns information about PUT networks/{id} HTTP operation
     *
     * @return array
     */
    public function putNetworkId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'networks/{id}',
            'jsonKey' => 'network',
            'params'  => [
                'id'   => $this->params->networkIdUrl(),
                'name' => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET networks/{id} HTTP operation
     *
     * @return array
     */
    public function getNetworkId()
    {
        return [
            'method' => 'GET',
            'path'   => 'networks/{id}',
            'params' => [
                'id' => $this->params->networkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE networks/{id} HTTP operation
     *
     * @return array
     */
    public function deleteNetworkId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'networks/{id}',
            'params' => [
                'id' => $this->params->networkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET security-groups/{security_group_id} HTTP
     * operation
     *
     * @return array
     */
    public function getSecurityGroupId()
    {
        return [
            'method' => 'GET',
            'path'   => 'security-groups/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE security-groups/{security_group_id} HTTP
     * operation
     *
     * @return array
     */
    public function deleteSecurityGroupId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'security-groups/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * security-group-rules/{rules-security-groups-id} HTTP operation
     *
     * @return array
     */
    public function deleteRulessecuritygroupsid()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'security-group-rules/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * security-group-rules/{rules-security-groups-id} HTTP operation
     *
     * @return array
     */
    public function getRulessecuritygroupsid()
    {
        return [
            'method' => 'GET',
            'path'   => 'security-group-rules/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    public function postFloatingIps()
    {
        return [
            'method' => 'POST',
            'path'   => 'floatingips',
            'params' => [
                'floatingNetworkId' => $this->params->floatingNetworkIdJson(),
                'fixedIpAddress'    => $this->params->fixedIpAddressJson(),
                'floatingIpAddress' => $this->params->floatingIpAddressJson(),
                'portId'            => $this->params->portIdJson(),
            ],
        ];
    }
}
