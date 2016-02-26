<?php

namespace Rackspace\Compute\v2;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET /limits HTTP operation
     *
     * @return array
     */
    public function getLimits()
    {
        return [
            'method' => 'GET',
            'path'   => 'limits',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /images HTTP operation
     *
     * @return array
     */
    public function getImages()
    {
        return [
            'method' => 'GET',
            'path'   => 'images',
            'params' => [
                'changessince' => $this->params->changessinceJson(),
                'limit'        => $this->params->limitJson(),
                'marker'       => $this->params->markerJson(),
                'name'         => $this->params->nameJson(),
                'status'       => $this->params->statusJson(),
                'type'         => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /images HTTP operation
     *
     * @return array
     */
    public function getImagesDetail()
    {
        $op = $this->getImages();
        $op['path'] .= '/detail';
        return $op;
    }

    /**
     * Returns information about POST /servers HTTP operation
     *
     * @return array
     */
    public function postServers()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers',
            'jsonKey' => 'server',
            'params'  => [
                'blockDevices' => $this->params->blockDeviceJson(),
                'flavorId'     => $this->params->flavorRefJson(),
                'imageId'      => $this->params->imageRefJson(),
                'maxCount'     => $this->params->maxCountJson(),
                'minCount'     => $this->params->minCountJson(),
                'name'         => $this->params->nameJson(),
                'networks'     => $this->params->networksJson(),
                'volumeSize'   => $this->params->volumeSizeJson(),
                'configDrive'  => $this->params->configDriveJson(),
                'diskConfig'   => $this->params->diskConfigJson(),
                'metadata'     => $this->params->metadataJson(),
                'personality'  => $this->params->personalityJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors HTTP operation
     *
     * @return array
     */
    public function getFlavors()
    {
        return [
            'method' => 'GET',
            'path'   => 'flavors',
            'params' => [
                'limit'  => $this->params->limitJson(),
                'marker' => $this->params->markerJson(),
                'minRam' => $this->params->minRamJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/detail HTTP operation
     *
     * @return array
     */
    public function getFlavorsDetail()
    {
        $op = $this->getFlavors();
        $op['path'] .= '/detail';
        return $op;
    }

    /**
     * Returns information about GET /servers HTTP operation
     *
     * @return array
     */
    public function getServers()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers',
            'params' => [
                'flavor' => $this->params->flavorJson(),
                'host'   => $this->params->hostJson(),
                'image'  => $this->params->imageJson(),
                'limit'  => $this->params->limitJson(),
                'marker' => $this->params->markerJson(),
                'name'   => $this->params->nameJson(),
                'status' => $this->params->statusJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/detail HTTP operation
     *
     * @return array
     */
    public function getServersDetail()
    {
        $op = $this->getServers();
        $op['path'] .= '/detail';
        return $op;
    }

    /**
     * Returns information about GET /extensions HTTP operation
     *
     * @return array
     */
    public function getExtensions()
    {
        return [
            'method' => 'GET',
            'path'   => 'extensions',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /os-volumes HTTP operation
     *
     * @return array
     */
    public function getOsvolumes()
    {
        return [
            'method' => 'GET',
            'path'   => 'os-volumes',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /os-volumes HTTP operation
     *
     * @return array
     */
    public function postOsvolumes()
    {
        return [
            'method'  => 'POST',
            'path'    => 'os-volumes',
            'jsonKey' => 'volume',
            'params'  => [
                'displayDescription' => $this->params->displayDescriptionJson(),
                'displayName'        => $this->params->displayNameJson(),
                'size'               => $this->params->sizeJson(),
                'volumeType'         => $this->params->volumeTypeJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /os-keypairs HTTP operation
     *
     * @return array
     */
    public function postOskeypairs()
    {
        return [
            'method'  => 'POST',
            'path'    => 'os-keypairs',
            'jsonKey' => 'keypair',
            'params'  => [
                'name'      => $this->params->nameJson(),
                'publicKey' => $this->params->publicKeyJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /os-keypairs HTTP operation
     *
     * @return array
     */
    public function getOskeypairs()
    {
        return [
            'method' => 'GET',
            'path'   => 'os-keypairs',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /os-networksv2 HTTP operation
     *
     * @return array
     */
    public function getOsnetworksv2()
    {
        return [
            'method' => 'GET',
            'path'   => 'os-networksv2',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /os-networksv2 HTTP operation
     *
     * @return array
     */
    public function postOsnetworksv2()
    {
        return [
            'method'  => 'POST',
            'path'    => 'os-networksv2',
            'jsonKey' => 'network',
            'params'  => [
                'cidr'  => $this->params->cidrJson(),
                'label' => $this->params->labelJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /os-volumes/{id} HTTP operation
     *
     * @return array
     */
    public function deleteOsVolumesId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'os-volumes/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /os-volumes/{id} HTTP operation
     *
     * @return array
     */
    public function getOsVolumesId()
    {
        return [
            'method' => 'GET',
            'path'   => 'os-volumes/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /images/{imageId} HTTP operation
     *
     * @return array
     */
    public function getImageId()
    {
        return [
            'method' => 'GET',
            'path'   => 'images/{id}',
            'params' => [
                'id' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{imageId} HTTP operation
     *
     * @return array
     */
    public function deleteImageId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'images/{id}',
            'params' => [
                'id' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /extensions/{alias} HTTP operation
     *
     * @return array
     */
    public function getExtensionsAlias()
    {
        return [
            'method' => 'GET',
            'path'   => 'extensions/{alias}',
            'params' => [
                'alias' => $this->params->aliasUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /servers/{id} HTTP operation
     *
     * @return array
     */
    public function putServerId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'servers/{id}',
            'jsonKey' => 'server',
            'params'  => [
                'id'         => $this->params->idUrl(),
                'name'       => $this->params->nameJson(),
                'accessIPv4' => $this->params->accessIPv4Json(),
                'accessIPv6' => $this->params->accessIPv6Json(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id} HTTP operation
     *
     * @return array
     */
    public function getServerId()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/{flavorId} HTTP operation
     *
     * @return array
     */
    public function getFlavorId()
    {
        return [
            'method' => 'GET',
            'path'   => 'flavors/{id}',
            'params' => [
                'id' => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /servers/{id} HTTP operation
     *
     * @return array
     */
    public function deleteServerId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'servers/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/ips HTTP operation
     *
     * @return array
     */
    public function getServersIps()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/ips',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /os-quota-sets/{tenant_id} HTTP operation
     *
     * @return array
     */
    public function getTenantId()
    {
        return [
            'method' => 'GET',
            'path'   => 'os-quota-sets/{id}',
            'params' => [
                'id' => $this->params->tenantIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function changePassword()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/action',
            'jsonKey' => 'changePassword',
            'params'  => [
                'id'       => $this->params->idUrl(),
                'password' => $this->params->adminPassJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function reboot()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/action',
            'jsonKey' => 'reboot',
            'params'  => [
                'id'   => $this->params->idUrl(),
                'type' => $this->params->rebootTypeJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function rebuild()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/action',
            'jsonKey' => 'rebuild',
            'params'  => [
                'id'          => $this->params->idUrl(),
                'name'        => $this->params->nameJson(),
                'imageId'     => $this->params->imageRefJson(),
                'accessIPv4'  => $this->params->accessIPv4Json(),
                'accessIPv6'  => $this->params->accessIPv6Json(),
                'password'    => $this->params->adminPassJson(),
                'metadata'    => $this->params->metadataJson(),
                'personality' => $this->params->personalityJson(),
                'diskConfig'  => $this->params->diskConfigJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function resize()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/action',
            'jsonKey' => 'resize',
            'params'  => [
                'id'         => $this->params->idUrl(),
                'flavorId'   => $this->params->flavorRefJson(),
                'diskConfig' => $this->params->diskConfigJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function confirmResize()
    {
        return [
            'method' => 'POST',
            'path'   => 'servers/{id}/action',
            'params' => [
                'id'            => $this->params->idUrl(),
                'confirmResize' => $this->params->confirmResizeJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function revertResize()
    {
        return [
            'method' => 'POST',
            'path'   => 'servers/{id}/action',
            'params' => [
                'id'           => $this->params->idUrl(),
                'revertResize' => $this->params->revertResizeJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function createImage()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/action',
            'jsonKey' => 'createImage',
            'params'  => [
                'id'       => $this->params->idUrl(),
                'name'     => $this->params->nameJson(),
                'metadata' => $this->params->metadataJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function rescue($refProvided = false)
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/action',
            'jsonKey' => $refProvided ? 'rescue' : '',
            'params'  => [
                'id'     => $this->params->idUrl(),
                'rescue' => $refProvided ? $this->params->rescueImageRefJson() : $this->params->rescueJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/action HTTP operation
     *
     * @return array
     */
    public function unrescue()
    {
        return [
            'method' => 'POST',
            'path'   => 'servers/{id}/action',
            'params' => [
                'id'       => $this->params->idUrl(),
                'unrescue' => $this->params->unrescueJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /os-keypairs/{keypair_name} HTTP operation
     *
     * @return array
     */
    public function deleteKeypairName()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'os-keypairs/{keypairName}',
            'params' => [
                'keypairName' => $this->params->keypairNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about a resource's metadata
     *
     * @return array
     */
    public function getMetadata($resource)
    {
        return [
            'method' => 'GET',
            'path'   => $resource . '/{id}/metadata',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /images/{imageId}/metadata HTTP operation
     *
     * @return array
     */
    public function postMetadata($resource)
    {
        return [
            'method' => 'POST',
            'path'   => $resource . '/{id}/metadata',
            'params' => [
                'id'       => $this->params->idUrl(),
                'metadata' => $this->params->metadataJson(),
            ],
        ];
    }

    /**
     * Returns information about metadata
     *
     * @return array
     */
    public function putMetadata($resource)
    {
        return [
            'method' => 'PUT',
            'path'   => $resource . '/{id}/metadata',
            'params' => [
                'id'       => $this->params->idUrl(),
                'metadata' => $this->params->metadataJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{imageId}/metadata/{key} HTTP
     * operation
     *
     * @return array
     */
    public function deleteMetadataKey($resource)
    {
        return [
            'method' => 'DELETE',
            'path'   => $resource . '/{id}/metadata/{key}',
            'params' => [
                'id'  => $this->params->imageIdUrl(),
                'key' => $this->params->keyUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /images/{imageId}/metadata/{key} HTTP operation
     *
     * @return array
     */
    public function putMetadataKey()
    {
        return [
            'method'  => 'PUT',
            'path'    => $resource . '/{id}/metadata/{key}',
            'jsonKey' => 'meta',
            'params'  => [
                'id'       => $this->params->imageIdUrl(),
                'key'      => $this->params->keyUrl(),
                'metadata' => $this->params->metadataJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /images/{imageId}/metadata/{key} HTTP operation
     *
     * @return array
     */
    public function getImagesMetadataKey()
    {
        return [
            'method' => 'GET',
            'path'   => 'images/{id}/metadata/{key}',
            'params' => [
                'id'  => $this->params->imageIdUrl(),
                'key' => $this->params->keyUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/{flavorId}/os-extra_specs HTTP operation
     *
     * @return array
     */
    public function getOsextraSpecs()
    {
        return [
            'method' => 'GET',
            'path'   => 'flavors/{id}/os-extra_specs',
            'params' => [
                'id' => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/ip_associations HTTP
     * operation
     *
     * @return array
     */
    public function getIpAssociations()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/ip_associations',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/ips/{network_label} HTTP
     * operation
     *
     * @return array
     */
    public function getNetworkLabel()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/ips/{networkLabel}',
            'params' => [
                'id'           => $this->params->idUrl(),
                'networkLabel' => $this->params->networkLabelUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/os-instance-actions HTTP
     * operation
     *
     * @return array
     */
    public function getOsinstanceactions()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/os-instance-actions',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/os-volume_attachments HTTP
     * operation
     *
     * @return array
     */
    public function getOsvolumeAttachments()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/os-volume_attachments',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /servers/{id}/os-volume_attachments HTTP
     * operation
     *
     * @return array
     */
    public function putOsvolumeAttachments()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'servers/{id}/os-volume_attachments',
            'jsonKey' => 'volumeAttachment',
            'params'  => [
                'id'       => $this->params->idUrl(),
                'device'   => $this->params->deviceJson(),
                'volumeId' => $this->params->volumeIdJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/rax-si-scheduled-image HTTP
     * operation
     *
     * @return array
     */
    public function postRaxsischeduledimage()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/rax-si-image-schedule',
            'jsonKey' => 'image_schedule',
            'params'  => [
                'id'        => $this->params->idUrl(),
                'dayOfWeek' => $this->params->dayOfWeekJson(),
                'retention' => $this->params->retentionJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/rax-si-scheduled-image HTTP
     * operation
     *
     * @return array
     */
    public function getRaxsischeduledimage()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/rax-si-image-schedule',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /servers/{id}/rax-si-scheduled-image
     * HTTP operation
     *
     * @return array
     */
    public function deleteRaxsischeduledimage()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'servers/{id}/rax-si-image-schedule',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/os-virtual-interfacesv2 HTTP
     * operation
     *
     * @return array
     */
    public function getOsvirtualinterfacesv2()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{id}/os-virtual-interfacesv2',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/os-virtual-interfacesv2 HTTP
     * operation
     *
     * @return array
     */
    public function postOsvirtualinterfacesv2()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{id}/os-virtual-interfacesv2',
            'jsonKey' => 'virtual_interface',
            'params'  => [
                'id'        => $this->params->idUrl(),
                'networkId' => $this->params->networkIdJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/{flavorId}/os-extra_specs/{key_id} HTTP
     * operation
     *
     * @return array
     */
    public function getFlavorExtraSpecs()
    {
        return [
            'method' => 'GET',
            'path'   => 'flavors/{id}/os-extra_specs',
            'params' => [
                'id' => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{id}/ip_associations/{IPAddressID}
     * HTTP operation
     *
     * @return array
     */
    public function postIPAddressID()
    {
        return [
            'method'  => 'POST',
            'path'    => 'servers/{serverId}/ip_associations/{ipAddressId}',
            'jsonKey' => 'key',
            'params'  => [
                'serverId'    => $this->params->idUrl(),
                'ipAddressId' => $this->params->iPAddressIDUrl(),
                'key'         => $this->params->keyJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{id}/ip_associations/{IPAddressID}
     * HTTP operation
     *
     * @return array
     */
    public function getIPAddressID()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{serverId}/ip_associations/{ipAddressId}',
            'params' => [
                'serverId'    => $this->params->idUrl(),
                'ipAddressId' => $this->params->iPAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /servers/{id}/ip_associations/{IPAddressID} HTTP operation
     *
     * @return array
     */
    public function deleteIPAddressID()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'servers/{serverId}/ip_associations/{ipAddressId}',
            'params' => [
                'serverId'    => $this->params->idUrl(),
                'ipAddressId' => $this->params->iPAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /servers/{id}/os-instance-actions/{request-id} HTTP operation
     *
     * @return array
     */
    public function getRequestid()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{serverId}/os-instance-actions/{requestId}',
            'params' => [
                'serverId'  => $this->params->idUrl(),
                'requestId' => $this->params->requestIdJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /servers/{id}/os-volume_attachments/{attachment_id} HTTP operation
     *
     * @return array
     */
    public function deleteAttachmentId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'servers/{serverId}/os-volume_attachments/{attachmentId}',
            'params' => [
                'serverId'     => $this->params->idUrl(),
                'attachmentId' => $this->params->attachmentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /servers/{id}/os-volume_attachments/{attachment_id} HTTP operation
     *
     * @return array
     */
    public function getAttachmentId()
    {
        return [
            'method' => 'GET',
            'path'   => 'servers/{serverId}/os-volume_attachments/{attachmentId}',
            'params' => [
                'serverId'     => $this->params->idUrl(),
                'attachmentId' => $this->params->attachmentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /servers/{id}/os-virtual-interfacesv2/{interface_id} HTTP operation
     *
     * @return array
     */
    public function deleteInterfaceId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'servers/{serverId}/os-virtual-interfacesv2/{id}',
            'params' => [
                'serverId' => $this->params->idUrl(),
                'id'       => $this->params->interfaceIdUrl(),
            ],
        ];
    }
}