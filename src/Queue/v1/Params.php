<?php

namespace Rackspace\Queue\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about projectId parameter
     *
     * @return array
     */
    public function projectIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'project_id',
        ];
    }

    /**
     * Returns information about detailed parameter
     *
     * @return array
     */
    public function detailedJson()
    {
        return [
            'type'        => self::BOOLEAN_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Determines whether queue metadata is included in the response. The default value for this parameter is 0830eb327dd13097bbe564904fe38c45c319cca4, which excludes the metadata.',
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
            'type'        => self::INTEGER_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Specifies the number of queues to return. The default value for the number of queues returned is 10. If you do not specify this parameter, the default number of queues is returned.',
        ];
    }

    /**
     * Returns information about queueName parameter
     *
     * @return array
     */
    public function queueNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'queue_name',
        ];
    }

    /**
     * Returns information about grace parameter
     *
     * @return array
     */
    public function graceJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
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
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about  parameter
     *
     * @return array
     */
    public function Json()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'ttl'  => $this->ttlJson(),
                'body' => $this->bodyJson(),
            ],
        ];
    }

    /**
     * Returns information about body parameter
     *
     * @return array
     */
    public function bodyJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'event' => $this->eventJson(),
            ],
        ];
    }

    /**
     * Returns information about event parameter
     *
     * @return array
     */
    public function eventJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about echo parameter
     *
     * @return array
     */
    public function echoJson()
    {
        return [
            'type'        => self::BOOLEAN_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Determines whether the API returns a client\'s own messages. The b15f33e534ece51504c2827b8f63b7dc4247f14f parameter is a Boolean value (dc6f4e904f99388e9b4d82d5fcdd67c0cff5f366 or39a6d4652cd47916fa0733d17a884734b16e663c) that determines whether the API returns a client\'s own messages, as determined by the 5aa091742590adebf2120fac18bf85f80964c5a8 portion of the User-Agent header. If you do not specify a value,f8aaeab5431abbd5d54fc340b6e58d2f479234e3 uses the default value of10bb54d4909b452d3edc87d64ca8596609e912bf. If you are experimenting with the API, you might want to seta44ff8bf1bb89fdf525f4eb8cc6330b1f8cf7c8d in order to see the messages that you posted.',
        ];
    }

    /**
     * Returns information about includeClaimed parameter
     *
     * @return array
     */
    public function includeClaimedJson()
    {
        return [
            'type'        => self::BOOLEAN_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Determines whether the API returns claimed messages and unclaimed messages. Theaddd6d225a478e518852fcbb10e21635c83fee7c parameter is a Boolean value (95a62ded7d59133ecf567a604119ee8f12b149b3 or078a870a5b5e0d26a8091a9b30da5f30e5f95f6b) that determines whether the API returns claimed messages and unclaimed messages. If you do not specify a value, c361b24afe1b7522229c9fcb8113de1f0982cfd2 uses the default value of 2b3d19762a58a4db3ed6473d600e0abba965b717 (only unclaimed messages are returned).',
            'sentAs'      => 'include_claimed',
        ];
    }

    /**
     * Returns information about new metadata parameter
     *
     * @return array
     */
    public function metadataJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about claimId parameter
     *
     * @return array
     */
    public function claimIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about messageId parameter
     *
     * @return array
     */
    public function messageIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }


}
