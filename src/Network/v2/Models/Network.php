<?php declare(strict_types=1);

namespace Rackspace\Network\v2\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Network resource in the Network v2 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Network extends OperatorResource implements Creatable, Updateable, Listable, Deletable, Retrievable
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
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postNetworks(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putNetworkId());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteNetworkId());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getNetworkId());
        $this->populateFromResponse($response);
    }
}
