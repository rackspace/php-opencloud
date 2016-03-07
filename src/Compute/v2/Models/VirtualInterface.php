<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;

/**
 * Represents a VirtualInterface resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class VirtualInterface extends AbstractResource implements Creatable, Listable, Deletable
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $ipAddresses;

    /**
     * @var string
     */
    public $macAddress;

    /**
     * @var string
     */
    public $serverId;

    protected $aliases = [
        'ip_addresses' => 'ipAddresses',
        'mac_address'  => 'macAddress',
    ];

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postOsvirtualinterfacesv2(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        // Use Rackspace\Compute\v2\Models\Server::deleteVirtualInterface() instead
    }
}