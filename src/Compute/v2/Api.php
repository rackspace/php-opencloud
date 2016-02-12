<?php

namespace Rackspace\Compute\v2;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    private $params;

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
            'path'   => '/limits',
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
            'path'   => '/images',
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
     * Returns information about POST /servers HTTP operation
     *
     * @return array
     */
    public function postServers()
    {
        return [
            'method'  => 'POST',
            'path'    => '/servers',
            'jsonKey' => 'server',
            'params'  => [
                'blockDeviceMappingV2' => $this->params->blockDeviceMappingV2Json(),
                'bootIndex'            => $this->params->bootIndexJson(),
                'deleteOnTermination'  => $this->params->deleteOnTerminationJson(),
                'destinationType'      => $this->params->destinationTypeJson(),
                'flavorRef'            => $this->params->flavorRefJson(),
                'imageRef'             => $this->params->imageRefJson(),
                'maxCount'             => $this->params->maxCountJson(),
                'minCount'             => $this->params->minCountJson(),
                'name'                 => $this->params->nameJson(),
                'networks'             => $this->params->networksJson(),
                'sourceType'           => $this->params->sourceTypeJson(),
                'uuid'                 => $this->params->uuidJson(),
                'volumeSize'           => $this->params->volumeSizeJson(),
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
            'path'   => '/flavors',
            'params' => [
                'limit'  => $this->params->limitJson(),
                'marker' => $this->params->markerJson(),
                'minRam' => $this->params->minRamJson(),
            ],
        ];
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
            'path'   => '/servers',
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
     * Returns information about GET /extensions HTTP operation
     *
     * @return array
     */
    public function getExtensions()
    {
        return [
            'method' => 'GET',
            'path'   => '/extensions',
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
            'path'   => '/os-volumes',
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
            'path'    => '/os-volumes',
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
            'path'    => '/os-keypairs',
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
            'path'   => '/os-keypairs',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /images/detail HTTP operation
     *
     * @return array
     */
    public function getDetail()
    {
        return [
            'method' => 'GET',
            'path'   => '/images/detail',
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
     * Returns information about GET /os-networksv2 HTTP operation
     *
     * @return array
     */
    public function getOsnetworksv2()
    {
        return [
            'method' => 'GET',
            'path'   => '/os-networksv2',
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
            'path'    => '/os-networksv2',
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
    public function deleteId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/os-volumes/{id}',
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
    public function getId()
    {
        return [
            'method' => 'GET',
            'path'   => '/os-volumes/{id}',
            'params' => [
                'id' => $this->params->idUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /images/{image_id} HTTP operation
     *
     * @return array
     */
    public function getImageId()
    {
        return [
            'method' => 'GET',
            'path'   => '/images/{image_id}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{image_id} HTTP operation
     *
     * @return array
     */
    public function deleteImageId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/images/{image_id}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /extensions/{alias} HTTP operation
     *
     * @return array
     */
    public function getAlias()
    {
        return [
            'method' => 'GET',
            'path'   => '/extensions/{alias}',
            'params' => [
                'alias' => $this->params->aliasUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /servers/{server_id} HTTP operation
     *
     * @return array
     */
    public function putServerId()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/servers/{server_id}',
            'jsonKey' => 'server',
            'params'  => [
                'serverId' => $this->params->serverIdUrl(),
                'name'     => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id} HTTP operation
     *
     * @return array
     */
    public function getServerId()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/{flavor_id} HTTP operation
     *
     * @return array
     */
    public function getFlavorId()
    {
        return [
            'method' => 'GET',
            'path'   => '/flavors/{flavor_id}',
            'params' => [
                'flavorId' => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /servers/{server_id} HTTP operation
     *
     * @return array
     */
    public function deleteServerId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/servers/{server_id}',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/ips HTTP operation
     *
     * @return array
     */
    public function getIps()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/ips',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
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
            'path'   => '/os-quota-sets/{tenant_id}',
            'params' => [
                'tenantId' => $this->params->tenantIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{server_id}/action HTTP operation
     *
     * @return array
     */
    public function postAction()
    {
        return [
            'method'  => 'POST',
            'path'    => '/servers/{server_id}/action',
            'jsonKey' => 'reboot',
            'params'  => [
                'serverId' => $this->params->serverIdUrl(),
                'type'     => $this->params->typeJson(),
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
            'path'   => '/os-keypairs/{keypair_name}',
            'params' => [
                'keypairName' => $this->params->keypairNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /images/{image_id}/metadata HTTP operation
     *
     * @return array
     */
    public function getMetadata()
    {
        return [
            'method' => 'GET',
            'path'   => '/images/{image_id}/metadata',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /images/{image_id}/metadata HTTP operation
     *
     * @return array
     */
    public function postMetadata()
    {
        return [
            'method'  => 'POST',
            'path'    => '/images/{image_id}/metadata',
            'jsonKey' => 'metadata',
            'params'  => [
                'imageId'  => $this->params->imageIdUrl(),
                'category' => $this->params->categoryJson(),
                'label'    => $this->params->labelJson(),
                'owner'    => $this->params->ownerJson(),
            ],
        ];
    }

    /**
     * Returns information about PUT /servers/{server_id}/metadata HTTP operation
     *
     * @return array
     */
    public function putMetadata()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/servers/{server_id}/metadata',
            'jsonKey' => 'metadata',
            'params'  => [
                'serverId' => $this->params->serverIdUrl(),
                'label'    => $this->params->labelJson(),
                'version'  => $this->params->versionJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{image_id}/metadata/{key} HTTP
     * operation
     *
     * @return array
     */
    public function deleteKey()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/images/{image_id}/metadata/{key}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
                'key'     => $this->params->keyUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /images/{image_id}/metadata/{key} HTTP operation
     *
     * @return array
     */
    public function putKey()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/images/{image_id}/metadata/{key}',
            'jsonKey' => 'meta',
            'params'  => [
                'imageId' => $this->params->imageIdUrl(),
                'key'     => $this->params->keyUrl(),
                'label'   => $this->params->labelJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /images/{image_id}/metadata/{key} HTTP operation
     *
     * @return array
     */
    public function getKey()
    {
        return [
            'method' => 'GET',
            'path'   => '/images/{image_id}/metadata/{key}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
                'key'     => $this->params->keyUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/{flavor_id}/os-extra_specs HTTP operation
     *
     * @return array
     */
    public function getOsextraSpecs()
    {
        return [
            'method' => 'GET',
            'path'   => '/flavors/{flavor_id}/os-extra_specs',
            'params' => [
                'flavorId' => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/ip_associations HTTP
     * operation
     *
     * @return array
     */
    public function getIpAssociations()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/ip_associations',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/ips/{network_label} HTTP
     * operation
     *
     * @return array
     */
    public function getNetworkLabel()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/ips/{network_label}',
            'params' => [
                'serverId'     => $this->params->serverIdUrl(),
                'networkLabel' => $this->params->networkLabelUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/os-instance-actions HTTP
     * operation
     *
     * @return array
     */
    public function getOsinstanceactions()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/os-instance-actions',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/os-volume_attachments HTTP
     * operation
     *
     * @return array
     */
    public function getOsvolumeAttachments()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/os-volume_attachments',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /servers/{server_id}/os-volume_attachments HTTP
     * operation
     *
     * @return array
     */
    public function putOsvolumeAttachments()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/servers/{server_id}/os-volume_attachments',
            'jsonKey' => 'volumeAttachment',
            'params'  => [
                'serverId' => $this->params->serverIdUrl(),
                'device'   => $this->params->deviceJson(),
                'volumeId' => $this->params->volumeIdJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{server_id}/rax-si-scheduled-image HTTP
     * operation
     *
     * @return array
     */
    public function postRaxsischeduledimage()
    {
        return [
            'method'  => 'POST',
            'path'    => '/servers/{server_id}/rax-si-scheduled-image',
            'jsonKey' => 'image_schedule',
            'params'  => [
                'serverId'  => $this->params->serverIdUrl(),
                'dayOfWeek' => $this->params->dayOfWeekJson(),
                'retention' => $this->params->retentionJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/rax-si-scheduled-image HTTP
     * operation
     *
     * @return array
     */
    public function getRaxsischeduledimage()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/rax-si-scheduled-image',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /servers/{server_id}/rax-si-scheduled-image
     * HTTP operation
     *
     * @return array
     */
    public function deleteRaxsischeduledimage()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/servers/{server_id}/rax-si-scheduled-image',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{server_id}/os-virtual-interfacesv2 HTTP
     * operation
     *
     * @return array
     */
    public function getOsvirtualinterfacesv2()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/os-virtual-interfacesv2',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{server_id}/os-virtual-interfacesv2 HTTP
     * operation
     *
     * @return array
     */
    public function postOsvirtualinterfacesv2()
    {
        return [
            'method'  => 'POST',
            'path'    => '/servers/{server_id}/os-virtual-interfacesv2',
            'jsonKey' => 'virtual_interface',
            'params'  => [
                'serverId'  => $this->params->serverIdUrl(),
                'networkId' => $this->params->networkIdJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /flavors/{flavor_id}/os-extra_specs/{key_id} HTTP
     * operation
     *
     * @return array
     */
    public function getKeyId()
    {
        return [
            'method' => 'GET',
            'path'   => '/flavors/{flavor_id}/os-extra_specs/{key_id}',
            'params' => [
                'flavorId' => $this->params->flavorIdUrl(),
                'keyId'    => $this->params->keyIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /servers/{serverID}/ip_associations/{IPAddressID}
     * HTTP operation
     *
     * @return array
     */
    public function postIPAddressID()
    {
        return [
            'method'  => 'POST',
            'path'    => '/servers/{serverID}/ip_associations/{IPAddressID}',
            'jsonKey' => 'key',
            'params'  => [
                'serverID'    => $this->params->serverIDUrl(),
                'iPAddressID' => $this->params->iPAddressIDUrl(),
                'key'         => $this->params->keyJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /servers/{serverID}/ip_associations/{IPAddressID}
     * HTTP operation
     *
     * @return array
     */
    public function getIPAddressID()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{serverID}/ip_associations/{IPAddressID}',
            'params' => [
                'serverID'    => $this->params->serverIDUrl(),
                'iPAddressID' => $this->params->iPAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /servers/{server_id}/ip_associations/{IPAddressID} HTTP operation
     *
     * @return array
     */
    public function deleteIPAddressID()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/servers/{server_id}/ip_associations/{IPAddressID}',
            'params' => [
                'serverId'    => $this->params->serverIdUrl(),
                'iPAddressID' => $this->params->iPAddressIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /servers/{server_id}/os-instance-actions/{request-id} HTTP operation
     *
     * @return array
     */
    public function getRequestid()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/os-instance-actions/{request-id}',
            'params' => [
                'serverId'  => $this->params->serverIdUrl(),
                'requestId' => $this->params->requestIdJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /servers/{server_id}/os-volume_attachments/{attachment_id} HTTP operation
     *
     * @return array
     */
    public function deleteAttachmentId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/servers/{server_id}/os-volume_attachments/{attachment_id}',
            'params' => [
                'serverId'     => $this->params->serverIdUrl(),
                'attachmentId' => $this->params->attachmentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /servers/{server_id}/os-volume_attachments/{attachment_id} HTTP operation
     *
     * @return array
     */
    public function getAttachmentId()
    {
        return [
            'method' => 'GET',
            'path'   => '/servers/{server_id}/os-volume_attachments/{attachment_id}',
            'params' => [
                'serverId'     => $this->params->serverIdUrl(),
                'attachmentId' => $this->params->attachmentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /servers/{server_id}/os-virtual-interfacesv2/{interface_id} HTTP operation
     *
     * @return array
     */
    public function deleteInterfaceId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/servers/{server_id}/os-virtual-interfacesv2/{interface_id}',
            'params' => [
                'serverId'    => $this->params->serverIdUrl(),
                'interfaceId' => $this->params->interfaceIdUrl(),
            ],
        ];
    }
}