<?php

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Configuration resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Configuration extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postConfiguration(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putConfiguration());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteConfiguration());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getConfiguration());
        return $this->populateFromResponse($response);
    }
}