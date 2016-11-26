<?php declare(strict_types=1);

namespace Rackspace\Queue\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Claim resource in the Queue v1 service
 *
 * @property \Rackspace\Queue\v1\Api $api
 */
class Claim extends OperatorResource implements Creatable, Updateable, Deletable
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
    public function create(array $userOptions): Creatable
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
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteClaim());
    }
}
