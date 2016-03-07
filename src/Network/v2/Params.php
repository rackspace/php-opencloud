<?php

namespace Rackspace\Network\v2;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about adminStateUp parameter
     *
     * @return array
     */
    public function adminStateUpJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'admin_state_up',
        ];
    }

    /**
     * Returns information about deviceId parameter
     *
     * @return array
     */
    public function deviceIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'device_id',
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
     * Returns information about networkId parameter
     *
     * @return array
     */
    public function networkIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'network_id',
        ];
    }

    /**
     * Returns information about allocationPools parameter
     *
     * @return array
     */
    public function allocationPoolsJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'allocation_pools',
            'items' => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'sentAs'     => 'allocation_pools',
                'properties' => [
                    'end'   => $this->endJson(),
                    'start' => $this->startJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about cidr parameter
     *
     * @return array
     */
    public function cidrJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about destination parameter
     *
     * @return array
     */
    public function destinationJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about end parameter
     *
     * @return array
     */
    public function endJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about gatewayIp parameter
     *
     * @return array
     */
    public function gatewayIpJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'gateway_ip',
        ];
    }

    /**
     * Returns information about hostRoutes parameter
     *
     * @return array
     */
    public function hostRoutesJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'host_routes',
            'items' => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'sentAs'     => 'host_routes',
                'properties' => [
                    'destination' => $this->destinationJson(),
                    'nexthop'     => $this->nexthopJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about ipVersion parameter
     *
     * @return array
     */
    public function ipVersionJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'ip_version',
        ];
    }

    /**
     * Returns information about nexthop parameter
     *
     * @return array
     */
    public function nexthopJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about start parameter
     *
     * @return array
     */
    public function startJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about tenantId parameter
     *
     * @return array
     */
    public function tenantIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'tenant_id',
        ];
    }

    /**
     * Returns information about shared parameter
     *
     * @return array
     */
    public function sharedJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about portIds parameter
     *
     * @return array
     */
    public function portIdsJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'port_ids',
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
                'sentAs'   => 'port_ids',
            ],
        ];
    }

    /**
     * Returns information about version parameter
     *
     * @return array
     */
    public function versionJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about description parameter
     *
     * @return array
     */
    public function descriptionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about portId parameter
     *
     * @return array
     */
    public function portIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'port_id',
        ];
    }

    /**
     * Returns information about subnetId parameter
     *
     * @return array
     */
    public function subnetIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'subnet_id',
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
     * Returns information about ipAddressID parameter
     *
     * @return array
     */
    public function ipAddressIDUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about serverID parameter
     *
     * @return array
     */
    public function serverIDUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about securityGroupId parameter
     *
     * @return array
     */
    public function securityGroupIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'security_group_id',
        ];
    }
}