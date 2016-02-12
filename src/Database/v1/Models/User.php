<?php

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a User resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class User extends AbstractResource implements Creatable, Updateable, Listable, Deletable
{
    /**
     * @var array
     */
    public $databases;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $name;

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postUser(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putUser());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteUser());
    }
}