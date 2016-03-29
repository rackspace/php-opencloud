<?php declare(strict_types=1);

namespace Rackspace\Image\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

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

    protected $aliases = [
        'created_at' => 'createdAt',
        'image_id'   => 'imageId',
        'member_id'  => 'memberId',
        'updated_at' => 'updatedAt',
    ];

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
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
        $this->populateFromResponse($response);
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
        $this->populateFromResponse($response);
    }
}