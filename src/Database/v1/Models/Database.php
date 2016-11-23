<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;

/**
 * Represents a Database resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Database extends OperatorResource implements Creatable, Listable, Deletable
{
    /**
     * @var string
     */
    public $name;

    protected $resourceKey = 'name';

    protected $resourcesKey = 'names';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postDatabase(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteDatabase());
    }
}
