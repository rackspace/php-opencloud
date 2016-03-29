<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;

/**
 * Represents a Database resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Database extends AbstractResource implements Creatable, Listable, Deletable
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