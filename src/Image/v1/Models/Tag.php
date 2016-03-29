<?php declare(strict_types=1);

namespace Rackspace\Image\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;

/**
 * Represents a Tag resource in the Image v1 service
 *
 * @property \Rackspace\Image\v1\Api $api
 */
class Tag extends AbstractResource implements Creatable, Deletable
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