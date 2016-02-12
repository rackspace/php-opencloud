<?php

namespace Rackspace\Database\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a ScheduledBackup resource in the Database v1 service
 *
 * @property \Rackspace\Database\v1\Api $api
 */
class ScheduledBackup extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $created;

    /**
     * @var NULL
     */
    public $dayOfMonth;

    /**
     * @var integer
     */
    public $dayOfWeek;

    /**
     * @var integer
     */
    public $hour;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $instanceId;

    /**
     * @var NULL
     */
    public $lastScheduled;

    /**
     * @var integer
     */
    public $minute;

    /**
     * @var NULL
     */
    public $month;

    /**
     * @var string
     */
    public $nextRun;

    /**
     * @var string
     */
    public $updated;

    protected $aliases = [
        'day_of_month'   => 'dayOfMonth',
        'day_of_week'    => 'dayOfWeek',
        'instance_id'    => 'instanceId',
        'last_scheduled' => 'lastScheduled',
        'next_run'       => 'nextRun',
    ];

    protected $resourceKey = 'schedule';

    protected $resourcesKey = 'schedules';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postScheduledBackup(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putScheduledBackup());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteScheduledBackup());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getScheduledBackup());
        return $this->populateFromResponse($response);
    }
}