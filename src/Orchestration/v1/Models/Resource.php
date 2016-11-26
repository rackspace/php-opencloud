<?php declare(strict_types=1);

namespace Rackspace\Orchestration\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Resource resource in the Network v1 service
 *
 * @property Rackspace\Network\v2\Api $api
 */
class Resource extends OperatorResource implements Creatable, Listable, Retrievable
{
    /**
     * @var object
     */
    public $attributes;

    /**
     * @var string
     */
    public $creationTime;

    /**
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $logicalResourceId;

    /**
     * @var string
     */
    public $physicalResourceId;

    /**
     * @var array
     */
    public $requiredBy;

    /**
     * @var string
     */
    public $resourceName;

    /**
     * @var string
     */
    public $resourceStatus;

    /**
     * @var string
     */
    public $resourceStatusReason;

    /**
     * @var string
     */
    public $resourceType;

    /**
     * @var string
     */
    public $updatedTime;

    protected $aliases = [
        'creation_time'          => 'creationTime',
        'logical_resource_id'    => 'logicalResourceId',
        'physical_resource_id'   => 'physicalResourceId',
        'required_by'            => 'requiredBy',
        'resource_name'          => 'resourceName',
        'resource_status'        => 'resourceStatus',
        'resource_status_reason' => 'resourceStatusReason',
        'resource_type'          => 'resourceType',
        'updated_time'           => 'updatedTime',
    ];

    protected $resourceKey = 'resource';

    protected $resourcesKey = 'resources';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postResource(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getResource());
        $this->populateFromResponse($response);
    }
}
