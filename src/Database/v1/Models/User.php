<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Updateable;

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
    public function create(array $userOptions): Creatable
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
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteUser());
    }
}