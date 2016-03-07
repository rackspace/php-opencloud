<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a VolumeAttachment resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class VolumeAttachment extends AbstractResource implements Listable, Retrievable
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
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getOsvolumeAttachment());
        return $this->populateFromResponse($response);
    }
}