<?php

namespace Rackspace\Queue\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Claim resource in the Queue v1 service
 *
 * @property \Rackspace\Queue\v1\Api $api
 */
class Claim extends AbstractResource implements Creatable, Updateable, Deletable
{
    /**
     * @var integer
     */
    public $ttl;

    /**
     * @var integer
     */
    public $grace;

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postClaim(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putClaim());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteClaim());
    }
}