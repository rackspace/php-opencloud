<?php declare(strict_types=1);

namespace Rackspace\Compute\v2;

use OpenCloud\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about changessince parameter
     *
     * @return array
     */
    public function changessinceJson()
    {
        return [
            'type'        => 'Datetime',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Filters the list of images to those that have changed since the changes-since time.',
        ];
    }

    /**
     * Returns information about limit parameter
     *
     * @return array
     */
    public function limitJson()
    {
        return [
            'type'        => 'Int',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Sets the page size.',
        ];
    }

    /**
     * Returns information about marker parameter
     *
     * @return array
     */
    public function markerJson()
    {
        return [
            'type'        => 'Uuid',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The ID of the last item in the previous list.',
        ];
    }

    /**
     * Returns information about name parameter
     *
     * @return array
     */
    public function nameJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Filters the list of images by image name.',
        ];
    }

    /**
     * Returns information about status parameter
     *
     * @return array
     */
    public function statusJson()
    {
        return [
            'type'        => 'Imagestatus',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Filters the list of images by status. In- flight images have a status of SAVING and the conditional progress element contains a value from 0 to 100, which indicates the percentage completion. Other possible values for the status attribute include ACTIVE, DELETED, ERROR, SAVING, and UNKNOWN. Images with an ACTIVE status are available for use.',
        ];
    }

    /**
     * Returns information about type parameter
     *
     * @return array
     */
    public function typeJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Filters Rackspace base images or any custom server images that you have created.',
        ];
    }

    /**
     * Returns information about type parameter
     *
     * @return array
     */
    public function rebootTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'required' => true,
            'location' => self::JSON,
            'enum'     => ['HARD', 'SOFT'],
        ];
    }

    /**
     * Returns information about blockDeviceMappingV2 parameter
     *
     * @return array
     */
    public function blockDeviceJson()
    {
        return [
            'type'     => self::ARRAY_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'block_device_mapping_v2',
            'items'    => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'bootIndex'           => $this->bootIndexJson(),
                    'uuid'                => $this->uuidJson(),
                    'volumeSize'          => $this->volumeSizeJson(),
                    'sourceType'          => $this->sourceTypeJson(),
                    'destinationType'     => $this->destinationTypeJson(),
                    'deleteOnTermination' => $this->deleteOnTerminationJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about bootIndex parameter
     *
     * @return array
     */
    public function bootIndexJson()
    {
        return [
            'type'     => self::INT_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'boot_index',
        ];
    }

    /**
     * Returns information about bootIndex parameter
     *
     * @return array
     */
    public function diskConfigJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'OS-DCF:diskConfig',
            'enum'        => ['AUTO', 'MANUAL'],
            'description' => 'The disk configuration value. The image auto_disk_config metadata key set will affect the value you can choose to set the server OS- DCF:diskConfig. If an image has auto_disk_config value of disabled, you cannot create a server from that image when specifying OS-DCF:diskConfig value of AUTO. Valid values are: AUTO:The server is built with a single partition which is the size of the target flavor disk. The file system is automatically adjusted to fit the entire partition. This keeps things simple and automated. AUTO is valid only for images and servers with a single partition that use the EXT3 file system. This is the default setting for applicable Rackspace base images. MANUAL:The server is built using the partition scheme and file system of the source image. If the target flavor disk is larger, the remaining disk space is left unpartitioned. This enables images to have non-EXT3 file systems, multiple partitions, and so on, and it enables you to manage the disk configuration.',
        ];
    }

    /**
     * Returns information about deleteOnTermination parameter
     *
     * @return array
     */
    public function deleteOnTerminationJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'delete_on_termination',
        ];
    }

    /**
     * Returns information about destinationType parameter
     *
     * @return array
     */
    public function destinationTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'destination_type',
        ];
    }

    /**
     * Returns information about flavorRef parameter
     *
     * @return array
     */
    public function flavorRefJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'flavorRef',
        ];
    }

    /**
     * Returns information about imageRef parameter
     *
     * @return array
     */
    public function imageRefJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'imageRef',
        ];
    }

    /**
     * Returns information about maxCount parameter
     *
     * @return array
     */
    public function maxCountJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'max_count',
        ];
    }

    /**
     * Returns information about minCount parameter
     *
     * @return array
     */
    public function minCountJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'min_count',
        ];
    }

    /**
     * Returns information about networks parameter
     *
     * @return array
     */
    public function networksJson()
    {
        return [
            'type'     => self::ARRAY_TYPE,
            'location' => self::JSON,
            'items'    => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'uuid' => $this->uuidJson(),
                    'port' => $this->portJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about sourceType parameter
     *
     * @return array
     */
    public function sourceTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'source_type',
        ];
    }

    /**
     * Returns information about uuid parameter
     *
     * @return array
     */
    public function uuidJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about port parameter
     *
     * @return array
     */
    public function portJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about volumeSize parameter
     *
     * @return array
     */
    public function volumeSizeJson()
    {
        return [
            'type'     => self::INT_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'volume_size',
        ];
    }

    /**
     * Returns information about configDrive parameter
     *
     * @return array
     */
    public function configDriveJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'config_drive',
        ];
    }

    /**
     * Returns information about metadata parameter
     *
     * @return array
     */
    public function metadataJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [],
        ];
    }

    /**
     * Returns information about contents parameter
     *
     * @return array
     */
    public function contentsJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about keyName parameter
     *
     * @return array
     */
    public function keyNameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'key_name',
        ];
    }

    /**
     * Returns information about path parameter
     *
     * @return array
     */
    public function pathJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about personality parameter
     *
     * @return array
     */
    public function personalityJson()
    {
        return [
            'type'     => self::ARRAY_TYPE,
            'location' => self::JSON,
            'items'    => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'path'     => $this->pathJson(),
                    'contents' => $this->contentsJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about minRam parameter
     *
     * @return array
     */
    public function minRamJson()
    {
        return [
            'type'        => 'Int',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Filters the list of flavors to those with the specified minimum amount of RAM in megabytes.',
        ];
    }

    /**
     * Returns information about flavor parameter
     *
     * @return array
     */
    public function flavorJson()
    {
        return [
            'type'        => 'Uuid',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The flavor reference for the desired flavor for your server instance.',
        ];
    }

    /**
     * Returns information about host parameter
     *
     * @return array
     */
    public function hostJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The name of the host.',
        ];
    }

    /**
     * Returns information about image parameter
     *
     * @return array
     */
    public function imageJson()
    {
        return [
            'type'        => 'Uuid',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The image reference for the desired image for your server instance.',
        ];
    }

    /**
     * Returns information about displayDescription parameter
     *
     * @return array
     */
    public function displayDescriptionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'display_description',
        ];
    }

    /**
     * Returns information about displayName parameter
     *
     * @return array
     */
    public function displayNameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'display_name',
        ];
    }

    /**
     * Returns information about size parameter
     *
     * @return array
     */
    public function sizeJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about volumeType parameter
     *
     * @return array
     */
    public function volumeTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'volume_type',
        ];
    }

    /**
     * Returns information about publicKey parameter
     *
     * @return array
     */
    public function publicKeyJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'public_key',
        ];
    }

    /**
     * Returns information about cidr parameter
     *
     * @return array
     */
    public function cidrJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about label parameter
     *
     * @return array
     */
    public function labelJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about id parameter
     *
     * @return array
     */
    public function idUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about imageId parameter
     *
     * @return array
     */
    public function imageIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'image_id',
        ];
    }

    /**
     * Returns information about alias parameter
     *
     * @return array
     */
    public function aliasUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about serverId parameter
     *
     * @return array
     */
    public function serverIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'server_id',
        ];
    }

    /**
     * Returns information about flavorId parameter
     *
     * @return array
     */
    public function flavorIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'flavor_id',
        ];
    }

    /**
     * Returns information about tenantId parameter
     *
     * @return array
     */
    public function tenantIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'tenant_id',
        ];
    }

    /**
     * Returns information about keypairName parameter
     *
     * @return array
     */
    public function keypairNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'keypair_name',
        ];
    }

    /**
     * Returns information about confirmResize parameter
     *
     * @return array
     */
    public function confirmResizeJson()
    {
        return [
            'type'     => self::NULL_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about rescue parameter
     *
     * @return array
     */
    public function rescueJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'enum'     => ['none'],
        ];
    }

    /**
     * Returns information about rescueImageRef parameter
     *
     * @return array
     */
    public function rescueImageRefJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'rescue_image_ref',
        ];
    }

    /**
     * Returns information about category parameter
     *
     * @return array
     */
    public function categoryJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'Category',
        ];
    }

    /**
     * Returns information about owner parameter
     *
     * @return array
     */
    public function ownerJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'Owner',
        ];
    }

    /**
     * Returns information about unrescue parameter
     *
     * @return array
     */
    public function unrescueJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about revertResize parameter
     *
     * @return array
     */
    public function revertResizeJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about adminPass parameter
     *
     * @return array
     */
    public function adminPassJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'adminPass',
        ];
    }

    /**
     * Returns information about imageType parameter
     *
     * @return array
     */
    public function imageTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'ImageType',
        ];
    }

    /**
     * Returns information about imageVersion parameter
     *
     * @return array
     */
    public function imageVersionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'ImageVersion',
        ];
    }

    /**
     * Returns information about version parameter
     *
     * @return array
     */
    public function versionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'Version',
        ];
    }

    /**
     * Returns information about key parameter
     *
     * @return array
     */
    public function keyUrl()
    {
        return [
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about networkLabel parameter
     *
     * @return array
     */
    public function networkLabelUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'network_label',
        ];
    }

    /**
     * Returns information about device parameter
     *
     * @return array
     */
    public function deviceJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about volumeId parameter
     *
     * @return array
     */
    public function volumeIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about dayOfWeek parameter
     *
     * @return array
     */
    public function dayOfWeekJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'day_of_week',
            'enum'     => ['MONDAY', 'TUESDAY', 'WEDNESDAY', ]
        ];
    }

    /**
     * Returns information about retention parameter
     *
     * @return array
     */
    public function retentionJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about networkId parameter
     *
     * @return array
     */
    public function networkIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'network_id',
        ];
    }

    /**
     * Returns information about keyId parameter
     *
     * @return array
     */
    public function keyIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'key_id',
        ];
    }

    /**
     * Returns information about iPAddressID parameter
     *
     * @return array
     */
    public function iPAddressIDUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'IPAddressID',
        ];
    }

    /**
     * Returns information about key parameter
     *
     * @return array
     */
    public function keyJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about id parameter
     *
     * @return array
     */
    public function idJson()
    {
        return [
            'type'        => 'Uuid',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The Associated IP address ID, which is not the actual IP address.',
        ];
    }

    /**
     * Returns information about requestId parameter
     *
     * @return array
     */
    public function requestIdJson()
    {
        return [
            'type'        => 'Uuid',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The server action request id.',
            'sentAs'      => 'request_id',
        ];
    }

    /**
     * Returns information about attachmentId parameter
     *
     * @return array
     */
    public function attachmentIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'attachment_id',
        ];
    }

    /**
     * Returns information about interfaceId parameter
     *
     * @return array
     */
    public function interfaceIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'interface_id',
        ];
    }

    /**
     * Returns information about accessIPv4 parameter
     *
     * @return array
     */
    public function accessIPv4Json()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about accessIPv6 parameter
     *
     * @return array
     */
    public function accessIPv6Json()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }
}