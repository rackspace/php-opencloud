<?php

namespace Rackspace\Compute\v2\Models;
use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Network resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Network extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
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
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postOsnetworksv2(), $userOptions);
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