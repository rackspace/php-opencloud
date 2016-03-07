<?php

namespace Rackspace\Network\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Network resource in the Network v2 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Network extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var boolean
     */
    public $adminStateUp;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $shared;

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    public $subnets;

    /**
     * @var string
     */
    public $tenantId;

    protected $aliases = [
        'admin_state_up' => 'adminStateUp',
        'tenant_id'      => 'tenantId',
    ];

    protected $resourceKey = 'network';

    protected $resourcesKey = 'networks';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postNetwork(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putNetwork());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteNetwork());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getNetwork());
        return $this->populateFromResponse($response);
    }
}