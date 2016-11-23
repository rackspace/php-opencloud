<?php declare(strict_types=1);

namespace Rackspace\Orchestration\v1\Models;

use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Event resource in the Network v1 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Event extends OperatorResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $eventTime;

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
    public $logicalResourceId;

    /**
     * @var NULL
     */
    public $physicalResourceId;

    /**
     * @var string
     */
    public $resourceName;

    /**
     * @var object
     */
    public $resourceProperties;

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

    protected $aliases = [
        'event_time'             => 'eventTime',
        'logical_resource_id'    => 'logicalResourceId',
        'physical_resource_id'   => 'physicalResourceId',
        'resource_name'          => 'resourceName',
        'resource_properties'    => 'resourceProperties',
        'resource_status'        => 'resourceStatus',
        'resource_status_reason' => 'resourceStatusReason',
        'resource_type'          => 'resourceType',
    ];

    protected $resourceKey = 'event';

    protected $resourcesKey = 'events';

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getEvent());
        $this->populateFromResponse($response);
    }
}
