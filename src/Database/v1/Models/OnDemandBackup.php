<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Backup resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class OnDemandBackup extends OperatorResource implements Creatable, Listable, Deletable, Retrievable
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
    public $description;

    /**
     * @var object
     */
    public $datastore;

    /**
     * @var string
     */
    public $id;

    /**
     * @var double
     */
    public $size;

    /**
     * @var integer
     */
    public $isAutomated;

    /**
     * @var string
     */
    public $name;

    /**
     * @var NULL
     */
    public $parentId;

    /**
     * @var string
     */
    public $created;

    /**
     * @var integer
     */
    public $flavorRam;

    /**
     * @var string
     */
    public $instanceId;

    /**
     * @var object
     */
    public $source;

    /**
     * @var string
     */
    public $locationRef;

    /**
     * @var string
     */
    public $type;

    /**
     * @var integer
     */
    public $volumeSize;

    protected $aliases = [
        'is_automated' => 'isAutomated',
        'parent_id'    => 'parentId',
        'flavor_ram'   => 'flavorRam',
        'instance_id'  => 'instanceId',
        'volume_size'  => 'volumeSize',
    ];

    protected $resourceKey = 'backup';

    protected $resourcesKey = 'backups';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postBackups(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteBackup());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getBackups());
        $this->populateFromResponse($response);
    }
}
