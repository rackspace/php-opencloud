<?php declare(strict_types=1);

namespace Rackspace\DNS\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about account parameter
     *
     * @return array
     */
    public function accountUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about comment parameter
     *
     * @return array
     */
    public function commentJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about data parameter
     *
     * @return array
     */
    public function dataJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about domains parameter
     *
     * @return array
     */
    public function domainsJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'name'         => $this->nameJson(),
                'comment'      => $this->commentJson(),
                'recordsList'  => $this->recordsListJson(),
                'subdomains'   => $this->subdomainsJson(),
                'ttl'          => $this->ttlJson(),
                'emailAddress' => $this->emailAddressJson(),
            ],
        ];
    }

    /**
     * Returns information about emailAddress parameter
     *
     * @return array
     */
    public function emailAddressJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
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
     * Returns information about records parameter
     *
     * @return array
     */
    public function recordsJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'items' => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'name' => $this->nameJson(),
                    'type' => $this->typeJson(),
                    'data' => $this->dataJson(),
                    'ttl'  => $this->ttlJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about recordsList parameter
     *
     * @return array
     */
    public function recordsListJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'records' => $this->recordsJson(),
            ],
        ];
    }

    /**
     * Returns information about subdomains parameter
     *
     * @return array
     */
    public function subdomainsJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'domains' => $this->domainsJson(),
            ],
        ];
    }

    /**
     * Returns information about ttl parameter
     *
     * @return array
     */
    public function ttlJson()
    {
        return [
            'type'     => self::INT_TYPE,
            'location' => self::JSON,
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
     * Returns information about id parameter
     *
     * @return array
     */
    public function idJson()
    {
        return [
            'type'     => self::INT_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about deleteSubdomains parameter
     *
     * @return array
     */
    public function deleteSubdomainsJson()
    {
        return [
            'type'        => 'String',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'If deleteSubdomains is true, also deletes subdomains. If false, subdomains are not deleted.',
        ];
    }

    /**
     * Returns information about type parameter
     *
     * @return array
     */
    public function typeUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about contentType parameter
     *
     * @return array
     */
    public function contentTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
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
     * Returns information about domainId parameter
     *
     * @return array
     */
    public function domainIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about showSubdomains parameter
     *
     * @return array
     */
    public function showSubdomainsJson()
    {
        return [
            'type'        => 'String',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'If showSubdomains is set to true, information about subdomains is returned. If showSubdomains is set to false, information about subdomains is not returned.',
        ];
    }

    /**
     * Returns information about recordId parameter
     *
     * @return array
     */
    public function recordIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }
}
