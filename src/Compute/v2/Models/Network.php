<?php declare(strict_types=1);

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Network resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Network extends OperatorResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $cidr;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $label;

    protected $resourceKey = 'network';
    protected $resourcesKey = 'networks';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postOsnetworksv2(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteOsnetworksv2());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getNetwork());
        $this->populateFromResponse($response);
    }
}
