<?php

namespace Rackspace\Monitoring\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about webhookToken parameter
     *
     * @return array
     */
    public function webhookTokenJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'webhook_token',
        ];
    }

    /**
     * Returns information about all parameter
     *
     * @return array
     */
    public function allJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about b parameter
     *
     * @return array
     */
    public function bJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about c parameter
     *
     * @return array
     */
    public function cJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about can parameter
     *
     * @return array
     */
    public function canJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about entityIpAddressesHashKey parameter
     *
     * @return array
     */
    public function entityIpAddressesHashKeyJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'entity_ip_addresses_hash_key',
        ];
    }

    /**
     * Returns information about here parameter
     *
     * @return array
     */
    public function hereJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about ipAddresses parameter
     *
     * @return array
     */
    public function ipAddressesJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'ip_addresses',
            'properties' => [
                'entityIpAddressesHashKey' => $this->entityIpAddressesHashKeyJson(),
                'b'                        => $this->bJson(),
                'c'                        => $this->cJson(),
                'test'                     => $this->testJson(),
            ],
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
     * Returns information about metadata parameter
     *
     * @return array
     */
    public function metadataJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'all'  => $this->allJson(),
                'of'   => $this->ofJson(),
                'can'  => $this->canJson(),
                'here' => $this->hereJson(),
            ],
        ];
    }

    /**
     * Returns information about of parameter
     *
     * @return array
     */
    public function ofJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about test parameter
     *
     * @return array
     */
    public function testJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about checks parameter
     *
     * @return array
     */
    public function checksJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
            ],
        ];
    }

    /**
     * Returns information about endTime parameter
     *
     * @return array
     */
    public function endTimeJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'end_time',
        ];
    }

    /**
     * Returns information about notificationPlans parameter
     *
     * @return array
     */
    public function notificationPlansJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'notification_plans',
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
                'sentAs'   => 'notification_plans',
            ],
        ];
    }

    /**
     * Returns information about startTime parameter
     *
     * @return array
     */
    public function startTimeJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'start_time',
        ];
    }

    /**
     * Returns information about address parameter
     *
     * @return array
     */
    public function addressJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about details parameter
     *
     * @return array
     */
    public function detailsJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'phoneNumber' => $this->phoneNumberJson(),
            ],
        ];
    }

    /**
     * Returns information about phoneNumber parameter
     *
     * @return array
     */
    public function phoneNumberJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'phone_number',
        ];
    }

    /**
     * Returns information about serviceKey parameter
     *
     * @return array
     */
    public function serviceKeyJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'service_key',
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
     * Returns information about url parameter
     *
     * @return array
     */
    public function urlJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about agentId parameter
     *
     * @return array
     */
    public function agentIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about criticalState parameter
     *
     * @return array
     */
    public function criticalStateJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'critical_state',
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
                'sentAs'   => 'critical_state',
            ],
        ];
    }

    /**
     * Returns information about okState parameter
     *
     * @return array
     */
    public function okStateJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'ok_state',
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
                'sentAs'   => 'ok_state',
            ],
        ];
    }

    /**
     * Returns information about warningState parameter
     *
     * @return array
     */
    public function warningStateJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'warning_state',
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
                'sentAs'   => 'warning_state',
            ],
        ];
    }

    /**
     * Returns information about entityId parameter
     *
     * @return array
     */
    public function entityIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about tokenId parameter
     *
     * @return array
     */
    public function tokenIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about checkTypeId parameter
     *
     * @return array
     */
    public function checkTypeIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about method parameter
     *
     * @return array
     */
    public function methodJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about monitoringZonesPoll parameter
     *
     * @return array
     */
    public function monitoringZonesPollJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'monitoring_zones_poll',
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
                'sentAs'   => 'monitoring_zones_poll',
            ],
        ];
    }

    /**
     * Returns information about period parameter
     *
     * @return array
     */
    public function periodJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about targetAlias parameter
     *
     * @return array
     */
    public function targetAliasJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'target_alias',
        ];
    }

    /**
     * Returns information about timeout parameter
     *
     * @return array
     */
    public function timeoutJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about checkId parameter
     *
     * @return array
     */
    public function checkIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'check_id',
        ];
    }

    /**
     * Returns information about criteria parameter
     *
     * @return array
     */
    public function criteriaJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about notificationPlanId parameter
     *
     * @return array
     */
    public function notificationPlanIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'notification_plan_id',
        ];
    }

    /**
     * Returns information about suppressionId parameter
     *
     * @return array
     */
    public function suppressionIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about entities parameter
     *
     * @return array
     */
    public function entitiesJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'items' => [
                'type'     => self::STRING_TYPE,
                'location' => self::JSON,
            ],
        ];
    }

    /**
     * Returns information about xAuthToken parameter
     *
     * @return array
     */
    public function xAuthTokenJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'A valid authentication token with administrative access. For details, see Get your credentials <auth-credentials>',
            'sentAs'      => 'XAuthToken',
        ];
    }

    /**
     * Returns information about available parameter
     *
     * @return array
     */
    public function availableJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about bytes parameter
     *
     * @return array
     */
    public function bytesJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'type' => $this->typeJson(),
                'data' => $this->dataJson(),
            ],
        ];
    }

    /**
     * Returns information about checkData parameter
     *
     * @return array
     */
    public function checkDataJson()
    {
        return [
            'type'       => self::ARRAY_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'check_data',
            'items' => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'sentAs'     => 'check_data',
                'properties' => [
                    'timestamp'        => $this->timestampJson(),
                    'monitoringZoneId' => $this->monitoringZoneIdJson(),
                    'available'        => $this->availableJson(),
                    'status'           => $this->statusJson(),
                    'metrics'          => $this->metricsJson(),
                ],
            ],
        ];
    }

    /**
     * Returns information about code parameter
     *
     * @return array
     */
    public function codeJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'type' => $this->typeJson(),
                'data' => $this->dataJson(),
            ],
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
     * Returns information about duration parameter
     *
     * @return array
     */
    public function durationJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'type' => $this->typeJson(),
                'data' => $this->dataJson(),
            ],
        ];
    }

    /**
     * Returns information about metrics parameter
     *
     * @return array
     */
    public function metricsJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'bytes'       => $this->bytesJson(),
                'ttFirstbyte' => $this->ttFirstbyteJson(),
                'ttConnect'   => $this->ttConnectJson(),
                'code'        => $this->codeJson(),
                'duration'    => $this->durationJson(),
            ],
        ];
    }

    /**
     * Returns information about monitoringZoneId parameter
     *
     * @return array
     */
    public function monitoringZoneIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'monitoring_zone_id',
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

    /**
     * Returns information about timestamp parameter
     *
     * @return array
     */
    public function timestampJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about ttConnect parameter
     *
     * @return array
     */
    public function ttConnectJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'tt_connect',
            'properties' => [
                'type' => $this->typeJson(),
                'data' => $this->dataJson(),
            ],
        ];
    }

    /**
     * Returns information about ttFirstbyte parameter
     *
     * @return array
     */
    public function ttFirstbyteJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'tt_firstbyte',
            'properties' => [
                'type' => $this->typeJson(),
                'data' => $this->dataJson(),
            ],
        ];
    }

    /**
     * Returns information about notificationId parameter
     *
     * @return array
     */
    public function notificationIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about alarmExampleId parameter
     *
     * @return array
     */
    public function alarmExampleIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about monitoringZoneId parameter
     *
     * @return array
     */
    public function monitoringZoneIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about checkId parameter
     *
     * @return array
     */
    public function checkIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about alarmId parameter
     *
     * @return array
     */
    public function alarmIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about connId parameter
     *
     * @return array
     */
    public function connIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about notificationPlanId parameter
     *
     * @return array
     */
    public function notificationPlanIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about notificationTypeId parameter
     *
     * @return array
     */
    public function notificationTypeIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about target parameter
     *
     * @return array
     */
    public function targetJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about targetResolver parameter
     *
     * @return array
     */
    public function targetResolverJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'target_resolver',
        ];
    }

    /**
     * Returns information about metricName parameter
     *
     * @return array
     */
    public function metricNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about agentCheckType parameter
     *
     * @return array
     */
    public function agentCheckTypeUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about uuid parameter
     *
     * @return array
     */
    public function uuidUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }
}