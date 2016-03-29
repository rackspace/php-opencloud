<?php declare(strict_types=1);

namespace Rackspace\Network\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

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