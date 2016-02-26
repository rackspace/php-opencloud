<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\HasMetadata;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;
use OpenStack\Common\Transport\Utils;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents a Server resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Server extends AbstractResource implements Creatable, Updateable, Listable, Deletable, Retrievable, HasMetadata
{
    /**
     * @var string
     */
    public $diskConfig;

    /**
     * @var integer
     */
    public $powerState;

    /**
     * @var string
     */
    public $taskState;

    /**
     * @var string
     */
    public $vmState;

    /**
     * @var string
     */
    public $accessIPv4;

    /**
     * @var string
     */
    public $accessIPv6;

    /**
     * @var object
     */
    public $addresses;

    /**
     * @var string
     */
    public $created;

    /**
     * @var object
     */
    public $flavor;

    /**
     * @var string
     */
    public $hostId;

    /**
     * @var string
     */
    public $id;

    /**
     * @var object
     */
    public $image;

    /**
     * @var array
     */
    public $links;

    /**
     * @var array
     */
    public $metadata = [];

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $progress;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $tenantId;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var string
     */
    public $userId;

    protected $aliases = [
        'OS-DCF:diskConfig'      => 'diskConfig',
        'OS-EXT-STS:power_state' => 'powerState',
        'OS-EXT-STS:task_state'  => 'taskState',
        'OS-EXT-STS:vm_state'    => 'vmState',
        'tenant_id'              => 'tenantId',
        'user_id'                => 'userId',
    ];

    protected $resourceKey = 'server';

    protected $resourcesKey = 'servers';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions)
    {
        $response = $this->execute($this->api->postServers(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putServerId());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteServerId());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getServerId());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata()
    {
        $response = $this->executeWithState($this->api->getMetadata('servers'));
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function mergeMetadata(array $metadata)
    {
        $this->metadata = array_merge($this->metadata, $metadata);
        $response = $this->executeWithState($this->api->postMetadata('servers'));
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function resetMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        $response = $this->executeWithState($this->api->putMetadata('servers'));
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function parseMetadata(ResponseInterface $response)
    {
        return Utils::jsonDecode($response)['metadata'];
    }

    public function attachVolume(array $options)
    {
        $this->execute($this->api->putOsvolumeAttachments(), $options + ['id' => $this->id]);
    }

    public function detachVolume($attachmentId)
    {
        $this->execute($this->api->deleteAttachmentId(), ['serverId' => $this->id, 'attachmentId' => $attachmentId]);
    }

    public function listVolumeAttachments()
    {
        $op = $this->api->getOsvolumeAttachments();
        return $this->model(VolumeAttachment::class)->enumerate($op, ['id' => $this->id]);
    }

    public function getVolumeAttachment($attachmentId)
    {
        return $this->model(VolumeAttachment::class, ['id' => $attachmentId, 'serverId' => $this->id]);
    }

    public function changePassword($password)
    {
        $this->execute($this->api->changePassword(), ['id' => $this->id, 'password' => $password]);
    }

    public function reboot($type = 'SOFT')
    {
        $this->execute($this->api->reboot(), ['id' => $this->id, 'type' => $type]);
    }

    public function rebuild(array $options)
    {
        $this->execute($this->api->rebuild(), $options + ['id' => $this->id]);
    }

    public function resize(array $options)
    {
        $this->execute($this->api->resize(), $options + ['id' => $this->id]);
    }

    public function confirmResize()
    {
        $this->execute($this->api->confirmResize(), ['id' => $this->id, 'confirmResize' => null]);
    }

    public function revertResize()
    {
        $this->execute($this->api->revertResize(), ['id' => $this->id, 'revertResize' => null]);
    }

    public function createImage(array $options)
    {
        $this->execute($this->api->createImage(), $options + ['id' => $this->id]);
    }

    public function rescue($rescueImageId = null)
    {
        $rescue = $rescueImageId ?: 'none';
        $this->execute($this->api->rescue($rescueImageId), ['id' => $this->id, 'rescue' => $rescue]);
    }

    public function unrescue()
    {
        $this->execute($this->api->unrescue(), ['id' => $this->id, 'unrescue' => null]);
    }

    public function getIpAddresses($networkLabel = null)
    {
        $response = $networkLabel
            ? $this->execute($this->api->getNetworkLabel(), ['id' => $this->id, 'networkLabel' => $networkLabel])
            : $this->executeWithState($this->api->getServersIps());

        return Utils::jsonDecode($response)['addresses'];
    }

    public function enableScheduledImages(array $options)
    {
        $this->execute($this->api->postRaxsischeduledimage(), $options + ['id' => $this->id]);
    }

    public function getScheduledImages()
    {
        $response = $this->execute($this->api->getRaxsischeduledimage(), ['id' => $this->id]);
        return $this->model(ImageSchedule::class)->populateFromResponse($response);
    }

    public function disableScheduledImages()
    {
        $this->execute($this->api->deleteRaxsischeduledimage(), ['id' => $this->id]);
    }

    public function listVirtualInterfaces()
    {
        $op = $this->api->getOsvirtualinterfacesv2();
        return $this->model(VirtualInterface::class)->enumerate($op, ['id' => $this->id]);
    }

    public function createVirtualInterface(array $options)
    {
        return $this->model(VirtualInterface::class)->create($options + ['id' => $this->id]);
    }

    public function deleteVirtualInterface($attachmentId)
    {
        $this->execute($this->api->deleteInterfaceId(), ['serverId' => $this->id, 'id' => $attachmentId]);
    }
}