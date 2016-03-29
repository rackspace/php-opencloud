<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a Instance resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Instance extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $created;

    /**
     * @var object
     */
    public $datastore;

    /**
     * @var object
     */
    public $flavor;

    /**
     * @var string
     */
    public $hostname;

    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var object
     */
    public $volume;

    protected $resourceKey = 'instance';

    protected $resourcesKey = 'instances';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postInstance(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteInstance());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getInstance());
        $this->populateFromResponse($response);
    }
}