<?php

namespace Rackspace\Compute\v2\Models;
use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a VolumeAttachment resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class VolumeAttachment extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $device;

    /**
     * @var string
     */
    public $serverId;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $volumeId;

    protected $resourceKey = 'volumeAttachment';

    protected $resourcesKey = 'volumeAttachments';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postVolumeAttachment(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteVolumeAttachment());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getVolumeAttachment());
        return $this->populateFromResponse($response);
    }
}