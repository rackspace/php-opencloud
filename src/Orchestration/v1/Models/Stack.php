<?php declare(strict_types=1);

namespace Rackspace\Orchestration\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Stack resource in the Network v1 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Stack extends OperatorResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var array
     */
    public $capabilities;

    /**
     * @var string
     */
    public $creationTime;

    /**
     * @var string
     */
    public $description;

    /**
     * @var boolean
     */
    public $disableRollback;

    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $links;

    /**
     * @var array
     */
    public $notificationTopics;

    /**
     * @var array
     */
    public $outputs;

    /**
     * @var object
     */
    public $parameters;

    /**
     * @var string
     */
    public $stackName;

    /**
     * @var string
     */
    public $stackStatus;

    /**
     * @var string
     */
    public $stackStatusReason;

    /**
     * @var string
     */
    public $templateDescription;

    /**
     * @var NULL
     */
    public $timeoutMins;

    /**
     * @var NULL
     */
    public $updatedTime;

    protected $aliases = [
        'creation_time'        => 'creationTime',
        'disable_rollback'     => 'disableRollback',
        'notification_topics'  => 'notificationTopics',
        'stack_name'           => 'stackName',
        'stack_status'         => 'stackStatus',
        'stack_status_reason'  => 'stackStatusReason',
        'template_description' => 'templateDescription',
        'timeout_mins'         => 'timeoutMins',
        'updated_time'         => 'updatedTime',
    ];

    protected $resourceKey = 'stack';

    protected $resourcesKey = 'stacks';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postStack(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putStack());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteStack());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getStack());
        $this->populateFromResponse($response);
    }
}
