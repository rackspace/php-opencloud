<?php declare(strict_types=1);

namespace Rackspace\Image\v1\Models;

use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Task resource in the Image v1 service
 *
 * @property \Rackspace\Image\v1\Api $api
 */
class Task extends OperatorResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $id;

    /**
     * @var object
     */
    public $input;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $owner;

    /**
     * @var string
     */
    public $schema;

    /**
     * @var string
     */
    public $self;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $updatedAt;

    protected $aliases = [
        'created_at' => 'createdAt',
        'updated_at' => 'updatedAt',
    ];

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getTask());
        $this->populateFromResponse($response);
    }
}
