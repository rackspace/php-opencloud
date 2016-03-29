<?php declare(strict_types=1);

namespace Rackspace\Database\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

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
    public function create(array $userOptions): Creatable
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
        $this->populateFromResponse($response);
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
        $this->populateFromResponse($response);
    }
}