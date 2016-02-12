<?php

namespace Rackspace\Image\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Member resource in the Image v1 service
 *
 * @property \Rackspace\Image\v1\Api $api
 */
class Member extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $imageId;

    /**
     * @var string
     */
    public $memberId;

    /**
     * @var string
     */
    public $schema;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $updatedAt;

    protected $aliases = array(
        'created_at' => 'createdAt',
        'image_id' => 'imageId',
        'member_id' => 'memberId',
        'updated_at' => 'updatedAt',
    );

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postMember(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putMember());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteMember());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getMember());
        return $this->populateFromResponse($response);
    }
}