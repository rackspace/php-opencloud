<?php

namespace Rackspace\Network\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Port resource in the Network v2 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Port extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var boolean
     */
    public $adminStateUp;

    /**
     * @var string
     */
    public $deviceId;

    /**
     * @var NULL
     */
    public $deviceOwner;

    /**
     * @var array
     */
    public $fixedIps;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $macAddress;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $networkId;

    /**
     * @var array
     */
    public $securityGroups;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $tenantId;

    protected $aliases = [
        'admin_state_up'  => 'adminStateUp',
        'device_id'       => 'deviceId',
        'device_owner'    => 'deviceOwner',
        'fixed_ips'       => 'fixedIps',
        'mac_address'     => 'macAddress',
        'network_id'      => 'networkId',
        'security_groups' => 'securityGroups',
        'tenant_id'       => 'tenantId',
    ];

    protected $resourceKey = 'port';

    protected $resourcesKey = 'ports';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postPort(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putPort());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deletePort());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getPort());
        return $this->populateFromResponse($response);
    }
}