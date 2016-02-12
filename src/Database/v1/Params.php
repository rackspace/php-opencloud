<?php

namespace Rackspace\Database\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about version parameter
     *
     * @return array
     */
    public function versionUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about accountId parameter
     *
     * @return array
     */
    public function accountIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about haId parameter
     *
     * @return array
     */
    public function haIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about backupId parameter
     *
     * @return array
     */
    public function backupIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
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
        ];
    }

    /**
     * Returns information about instanceId parameter
     *
     * @return array
     */
    public function instanceIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about scheduleId parameter
     *
     * @return array
     */
    public function scheduleIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about datastoreId parameter
     *
     * @return array
     */
    public function datastoreIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about address parameter
     *
     * @return array
     */
    public function addressUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about configId parameter
     *
     * @return array
     */
    public function configIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about name parameter
     *
     * @return array
     */
    public function nameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about versionId parameter
     *
     * @return array
     */
    public function versionIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about databaseName parameter
     *
     * @return array
     */
    public function databaseNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about datastoreType parameter
     *
     * @return array
     */
    public function datastoreTypeUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about parameterId parameter
     *
     * @return array
     */
    public function parameterIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }
}