<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Replica resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class Replica extends OperatorResource implements Creatable, Listable, Retrievable
{
    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $created;

    /**
     * @var array
     */
    public $ip;

    /**
     * @var string
     */
    public $id;

    /**
     * @var object
     */
    public $volume;

    /**
     * @var object
     */
    public $flavor;

    /**
     * @var object
     */
    public $datastore;

    /**
     * @var object
     */
    public $replicaOf;

    protected $aliases = [
        'replica_of' => 'replicaOf',
    ];

    protected $resourceKey = 'instance';

    protected $resourcesKey = 'instances';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postReplica(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getReplica());
        $this->populateFromResponse($response);
    }
}
