<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;

/**
 * Represents a VirtualIp resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class VirtualIp extends OperatorResource implements Creatable, Listable, Deletable
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $type;

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postVirtualIp(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteVirtualIp());
    }
}
