<?php

namespace Rackspace\Queue\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;

/**
 * Represents a Queue resource in the Queue v1 service
 *
 * @property \Rackspace\Queue\v1\Api $api
 */
class Queue extends AbstractResource implements Creatable, Listable, Deletable
{
    /**
     * @var string
     */
    public $href;

    /**
     * @var string
     */
    public $name;

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postQueue(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteQueue());
    }
}