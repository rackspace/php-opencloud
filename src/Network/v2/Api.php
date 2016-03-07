<?php

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
     * Returns information about GET /v2.0/ports HTTP operation
     *
     * @return array
     */
    public function getPorts()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/ports',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /v2.0/ports HTTP operation
     *
     * @return array
     */
    public function postPorts()
    {
        return [
            'method'  => 'POST',
            'path'    => '/v2.0/ports',
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
     * Returns information about GET /v2.0/subnets HTTP operation
     *
     * @return array
     */
    public function getSubnets()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/subnets',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /v2.0/subnets HTTP operation
     *
     * @return array
     */
    public function postSubnets()
    {
        return [
            'method'  => 'POST',
            'path'    => '/v2.0/subnets',
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
     * Returns information about POST /v2.0/networks HTTP operation
     *
     * @return array
     */
    public function postNetworks()
    {
        return [
            'method'  => 'POST',
            'path'    => '/v2.0/networks',
            'jsonKey' => 'network',
            'params'  => [
                'name'     => $this->params->nameJson(),
                'shared'   => $this->params->sharedJson(),
                'tenantId' => $this->params->tenantIdJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/networks HTTP operation
     *
     * @return array
     */
    public function getNetworks()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/networks',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /v2.0/ip_addresses HTTP operation
     *
     * @return array
     */
    public function getIpAddresses()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/ip_addresses',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /v2.0/ip_addresses HTTP operation
     *
     * @return array
     */
    public function postIpAddresses()
    {
        return [
            'method'  => 'POST',
            'path'    => '/v2.0/ip_addresses',
            'jsonKey' => 'ip_address',
            'params'  => [
                'networkId' => $this->params->networkIdJson(),
                'portIds'   => $this->params->portIdsJson(),
                'tenantId'  => $this->params->tenantIdJson(),
                'version'   => $this->params->versionJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /v2.0/security-groups HTTP operation
     *
     * @return array
     */
    public function postSecuritygroups()
    {
        return [
            'method'  => 'POST',
            'path'    => '/v2.0/security-groups',
            'jsonKey' => 'security_group',
            'params'  => [
                'description' => $this->params->descriptionJson(),
                'name'        => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/ports/{port_id} HTTP operation
     *
     * @return array
     */
    public function getPortId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/ports/{port_id}',
            'params' => [
                'portId' => $this->params->portIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /v2.0/ports/{port_id} HTTP operation
     *
     * @return array
     */
    public function putPortId()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/v2.0/ports/{port_id}',
            'jsonKey' => 'port',
            'params'  => [
                'portId'       => $this->params->portIdUrl(),
                'adminStateUp' => $this->params->adminStateUpJson(),
                'deviceId'     => $this->params->deviceIdJson(),
                'name'         => $this->params->nameJson(),
                'networkId'    => $this->params->networkIdJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v2.0/ports/{port_id} HTTP operation
     *
     * @return array
     */
    public function deletePortId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v2.0/ports/{port_id}',
            'params' => [
                'portId' => $this->params->portIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/security-groups HTTP operation
     *
     * @return array
     */
    public function getSecuritygroups()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/security-groups',
            'params' => [],
        ];
    }

    /**
     * Returns information about DELETE /v2.0/subnets/{subnet_id} HTTP operation
     *
     * @return array
     */
    public function deleteSubnetId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v2.0/subnets/{subnet_id}',
            'params' => [
                'subnetId' => $this->params->subnetIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/subnets/{subnet_id} HTTP operation
     *
     * @return array
     */
    public function getSubnetId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/subnets/{subnet_id}',
            'params' => [
                'subnetId' => $this->params->subnetIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /v2.0/subnets/{subnet_id} HTTP operation
     *
     * @return array
     */
    public function putSubnetId()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/v2.0/subnets/{subnet_id}',
            'jsonKey' => 'subnet',
            'params'  => [
                'subnetId' => $this->params->subnetIdUrl(),
                'name'     => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/security-group-rules HTTP operation
     *
     * @return array
     */
    public function getSecuritygrouprules()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/security-group-rules',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /v2.0/security-group-rules HTTP operation
     *
     * @return array
     */
    public function postSecuritygrouprules()
    {
        return [
            'method' => 'POST',
            'path'   => '/v2.0/security-group-rules',
            'params' => [],
        ];
    }

    /**
     * Returns information about PUT /v2.0/networks/{network_id} HTTP operation
     *
     * @return array
     */
    public function putNetworkId()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/v2.0/networks/{network_id}',
            'jsonKey' => 'network',
            'params'  => [
                'networkId' => $this->params->networkIdUrl(),
                'name'      => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/networks/{network_id} HTTP operation
     *
     * @return array
     */
    public function getNetworkId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/networks/{network_id}',
            'params' => [
                'networkId' => $this->params->networkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v2.0/networks/{network_id} HTTP operation
     *
     * @return array
     */
    public function deleteNetworkId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v2.0/networks/{network_id}',
            'params' => [
                'networkId' => $this->params->networkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v2.0/ip_addresses/{ipAddressID} HTTP operation
     *
     * @return array
     */
    public function deleteIpAddressID()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v2.0/ip_addresses/{ipAddressID}',
            'params' => [
                'ipAddressID' => $this->params->ipAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /v2.0/ip_addresses/{ipAddressID} HTTP operation
     *
     * @return array
     */
    public function putIpAddressID()
    {
        return [
            'method' => 'PUT',
            'path'   => '/v2.0/ip_addresses/{ipAddressID}',
            'params' => [
                'ipAddressID' => $this->params->ipAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/ip_addresses/{ipAddressID} HTTP operation
     *
     * @return array
     */
    public function getIpAddressID()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/ip_addresses/{ipAddressID}',
            'params' => [
                'ipAddressID' => $this->params->ipAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2/servers/{serverID}/ip_associations HTTP
     * operation
     *
     * @return array
     */
    public function getIpAssociations()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2/servers/{serverID}/ip_associations',
            'params' => [
                'serverID' => $this->params->serverIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v2.0/security-groups/{security_group_id} HTTP
     * operation
     *
     * @return array
     */
    public function getSecurityGroupId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/security-groups/{security_group_id}',
            'params' => [
                'securityGroupId' => $this->params->securityGroupIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v2.0/security-groups/{security_group_id} HTTP
     * operation
     *
     * @return array
     */
    public function deleteSecurityGroupId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v2.0/security-groups/{security_group_id}',
            'params' => [
                'securityGroupId' => $this->params->securityGroupIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /v2.0/security-group-rules/{rules-security-groups-id} HTTP operation
     *
     * @return array
     */
    public function deleteRulessecuritygroupsid()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v2.0/security-group-rules/{rules-security-groups-id}',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET
     * /v2.0/security-group-rules/{rules-security-groups-id} HTTP operation
     *
     * @return array
     */
    public function getRulessecuritygroupsid()
    {
        return [
            'method' => 'GET',
            'path'   => '/v2.0/security-group-rules/{rules-security-groups-id}',
            'params' => [],
        ];
    }
}