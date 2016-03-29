<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a HaInstance resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class HaInstance extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var object
     */
    public $volume;

    /**
     * @var object
     */
    public $flavor;

    /**
     * @var array
     */
    public $replicas;

    /**
     * @var array
     */
    public $replicaSource;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $state;

    /**
     * @var array
     */
    public $acls;

    /**
     * @var object
     */
    public $datastore;

    /**
     * @var array
     */
    public $networks;

    protected $aliases = [
        'tenant_id'      => 'tenantId',
        'replica_source' => 'replicaSource',
    ];

    protected $resourceKey = 'ha_instance';

    protected $resourcesKey = 'ha_instances';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postHaInstance(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteHaInstance());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getHaInstance());
        $this->populateFromResponse($response);
    }
}