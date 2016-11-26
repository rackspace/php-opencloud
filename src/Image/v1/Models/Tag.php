<?php declare(strict_types=1);

namespace Rackspace\Image\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;

/**
 * Represents a Tag resource in the Image v1 service
 *
 * @property \Rackspace\Image\v1\Api $api
 */
class Tag extends OperatorResource implements Creatable, Deletable
{
    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postTag(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteTag());
    }
}
