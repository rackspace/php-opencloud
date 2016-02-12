<?php

namespace Rackspace\Image\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about imageUuid parameter
     *
     * @return array
     */
    public function imageUuidJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'image_uuid',
        ];
    }

    /**
     * Returns information about input parameter
     *
     * @return array
     */
    public function inputJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'properties'  => [
                'imageUuid'               => $this->imageUuidJson(),
                'receivingSwiftContainer' => $this->receivingSwiftContainerJson(),
            ],
            'required'    => false,
            'description' => 'The container for export input parameters.',
        ];
    }

    /**
     * Returns information about receivingSwiftContainer parameter
     *
     * @return array
     */
    public function receivingSwiftContainerJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'receiving_swift_container',
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
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about imageProperties parameter
     *
     * @return array
     */
    public function imagePropertiesJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'image_properties',
            'properties' => [
                'name' => $this->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about importFrom parameter
     *
     * @return array
     */
    public function importFromJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'import_from',
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
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about taskID parameter
     *
     * @return array
     */
    public function taskIDUrl()
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
     * Returns information about member parameter
     *
     * @return array
     */
    public function memberJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about tag parameter
     *
     * @return array
     */
    public function tagUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about memberId parameter
     *
     * @return array
     */
    public function memberIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'member_id',
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
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }
}