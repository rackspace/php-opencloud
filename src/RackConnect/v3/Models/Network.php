<?php

namespace Rackspace\RackConnect\v3\Models;
use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Network resource in the RackConnect v3 service
 *
 * @property \Rackspace\RackConnect\v3\Api $api
 */
class Network extends AbstractResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $cidr;

    /**
     * @var string
     */
    public $created;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $updated;

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getNetwork());
        return $this->populateFromResponse($response);
    }
}