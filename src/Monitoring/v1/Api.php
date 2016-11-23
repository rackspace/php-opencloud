<?php declare(strict_types=1);

namespace Rackspace\Monitoring\v1;

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
     * Returns information about GET /agents HTTP operation
     *
     * @return array
     */
    public function getAgents()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /audits HTTP operation
     *
     * @return array
     */
    public function getAudits()
    {
        return [
            'method' => 'GET',
            'path'   => 'audits',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /account HTTP operation
     *
     * @return array
     */
    public function getAccount()
    {
        return [
            'method' => 'GET',
            'path'   => 'account',
            'params' => [],
        ];
    }

    /**
     * Returns information about PUT /account HTTP operation
     *
     * @return array
     */
    public function putAccount()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'account',
            'jsonKey' => 'webhook_token',
            'params'  => [
                'webhookToken' => $this->params->webhookTokenJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /entities HTTP operation
     *
     * @return array
     */
    public function getEntities()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /entities HTTP operation
     *
     * @return array
     */
    public function postEntities()
    {
        return [
            'method' => 'POST',
            'path'   => 'entities',
            'params' => [
                'all'                      => $this->params->allJson(),
                'b'                        => $this->params->bJson(),
                'c'                        => $this->params->cJson(),
                'can'                      => $this->params->canJson(),
                'entityIpAddressesHashKey' => $this->params->entityIpAddressesHashKeyJson(),
                'here'                     => $this->params->hereJson(),
                'ipAddresses'              => $this->params->ipAddressesJson(),
                'label'                    => $this->params->labelJson(),
                'metadata'                 => $this->params->metadataJson(),
                'of'                       => $this->params->ofJson(),
                'test'                     => $this->params->testJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /check_types HTTP operation
     *
     * @return array
     */
    public function getCheckTypes()
    {
        return [
            'method' => 'GET',
            'path'   => 'check_types',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /suppressions HTTP operation
     *
     * @return array
     */
    public function getSuppressions()
    {
        return [
            'method' => 'GET',
            'path'   => 'suppressions',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /suppressions HTTP operation
     *
     * @return array
     */
    public function postSuppressions()
    {
        return [
            'method' => 'POST',
            'path'   => 'suppressions',
            'params' => [
                'checks'            => $this->params->checksJson(),
                'endTime'           => $this->params->endTimeJson(),
                'notificationPlans' => $this->params->notificationPlansJson(),
                'startTime'         => $this->params->startTimeJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /agent_tokens HTTP operation
     *
     * @return array
     */
    public function postAgentTokens()
    {
        return [
            'method'  => 'POST',
            'path'    => 'agent_tokens',
            'jsonKey' => 'label',
            'params'  => [
                'label' => $this->params->labelJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /agent_tokens HTTP operation
     *
     * @return array
     */
    public function getAgentTokens()
    {
        return [
            'method' => 'GET',
            'path'   => 'agent_tokens',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /notifications HTTP operation
     *
     * @return array
     */
    public function getNotifications()
    {
        return [
            'method' => 'GET',
            'path'   => 'notifications',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /notifications HTTP operation
     *
     * @return array
     */
    public function postNotifications()
    {
        return [
            'method' => 'POST',
            'path'   => 'notifications',
            'params' => [
                'address'     => $this->params->addressJson(),
                'details'     => $this->params->detailsJson(),
                'label'       => $this->params->labelJson(),
                'phoneNumber' => $this->params->phoneNumberJson(),
                'serviceKey'  => $this->params->serviceKeyJson(),
                'type'        => $this->params->typeJson(),
                'url'         => $this->params->urlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /alarm_examples HTTP operation
     *
     * @return array
     */
    public function getAlarmExamples()
    {
        return [
            'method' => 'GET',
            'path'   => 'alarm_examples',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /views/overview HTTP operation
     *
     * @return array
     */
    public function getOverview()
    {
        return [
            'method' => 'GET',
            'path'   => 'views/overview',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId} HTTP operation
     *
     * @return array
     */
    public function getAgentId()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /suppression_logs HTTP operation
     *
     * @return array
     */
    public function getSuppressionLogs()
    {
        return [
            'method' => 'GET',
            'path'   => 'suppression_logs',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /monitoring_zones HTTP operation
     *
     * @return array
     */
    public function getMonitoringZones()
    {
        return [
            'method' => 'GET',
            'path'   => 'monitoring_zones',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /changelogs/alarms HTTP operation
     *
     * @return array
     */
    public function getAlarms()
    {
        return [
            'method' => 'GET',
            'path'   => 'changelogs/alarms',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /test-notification HTTP operation
     *
     * @return array
     */
    public function postTestnotification()
    {
        return [
            'method' => 'POST',
            'path'   => 'test-notification',
            'params' => [
                'details' => $this->params->detailsJson(),
                'type'    => $this->params->typeJson(),
                'url'     => $this->params->urlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /notification_types HTTP operation
     *
     * @return array
     */
    public function getNotificationTypes()
    {
        return [
            'method' => 'GET',
            'path'   => 'notification_types',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /notification_plans HTTP operation
     *
     * @return array
     */
    public function getNotificationPlans()
    {
        return [
            'method' => 'GET',
            'path'   => 'notification_plans',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /notification_plans HTTP operation
     *
     * @return array
     */
    public function postNotificationPlans()
    {
        return [
            'method' => 'POST',
            'path'   => 'notification_plans',
            'params' => [
                'criticalState' => $this->params->criticalStateJson(),
                'label'         => $this->params->labelJson(),
                'okState'       => $this->params->okStateJson(),
                'warningState'  => $this->params->warningStateJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /entities/{entityId} HTTP operation
     *
     * @return array
     */
    public function getEntityId()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /entities/{entityId} HTTP operation
     *
     * @return array
     */
    public function putEntityId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'entities/{entityId}',
            'jsonKey' => 'label',
            'params'  => [
                'entityId' => $this->params->entityIdUrl(),
                'label'    => $this->params->labelJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /entities/{entityId} HTTP operation
     *
     * @return array
     */
    public function deleteEntityId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'entities/{entityId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /agent_tokens/{tokenId} HTTP operation
     *
     * @return array
     */
    public function putTokenId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'agent_tokens/{tokenId}',
            'jsonKey' => 'label',
            'params'  => [
                'tokenId' => $this->params->tokenIdUrl(),
                'label'   => $this->params->labelJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /agent_tokens/{tokenId} HTTP operation
     *
     * @return array
     */
    public function getTokenId()
    {
        return [
            'method' => 'GET',
            'path'   => 'agent_tokens/{tokenId}',
            'params' => [
                'tokenId' => $this->params->tokenIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /agent_tokens/{tokenId} HTTP operation
     *
     * @return array
     */
    public function deleteTokenId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'agent_tokens/{tokenId}',
            'params' => [
                'tokenId' => $this->params->tokenIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /check_types/{checkTypeId} HTTP operation
     *
     * @return array
     */
    public function getCheckTypeId()
    {
        return [
            'method' => 'GET',
            'path'   => 'check_types/{checkTypeId}',
            'params' => [
                'checkTypeId' => $this->params->checkTypeIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /entities/{entityId}/checks HTTP operation
     *
     * @return array
     */
    public function postChecks()
    {
        return [
            'method' => 'POST',
            'path'   => 'entities/{entityId}/checks',
            'params' => [
                'entityId'            => $this->params->entityIdUrl(),
                'details'             => $this->params->detailsJson(),
                'label'               => $this->params->labelJson(),
                'method'              => $this->params->methodJson(),
                'monitoringZonesPoll' => $this->params->monitoringZonesPollJson(),
                'period'              => $this->params->periodJson(),
                'targetAlias'         => $this->params->targetAliasJson(),
                'timeout'             => $this->params->timeoutJson(),
                'type'                => $this->params->typeJson(),
                'url'                 => $this->params->urlJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /entities/{entityId}/alarms HTTP operation
     *
     * @return array
     */
    public function postAlarms()
    {
        return [
            'method' => 'POST',
            'path'   => 'entities/{entityId}/alarms',
            'params' => [
                'entityId'           => $this->params->entityIdUrl(),
                'checkId'            => $this->params->checkIdJson(),
                'criteria'           => $this->params->criteriaJson(),
                'notificationPlanId' => $this->params->notificationPlanIdJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /entities/{entityId}/checks HTTP operation
     *
     * @return array
     */
    public function getChecks()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/checks',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /suppressions/{suppressionId} HTTP operation
     *
     * @return array
     */
    public function putSuppressionId()
    {
        return [
            'method' => 'PUT',
            'path'   => 'suppressions/{suppressionId}',
            'params' => [
                'suppressionId' => $this->params->suppressionIdUrl(),
                'endTime'       => $this->params->endTimeJson(),
                'entities'      => $this->params->entitiesJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /suppressions/{suppressionId} HTTP operation
     *
     * @return array
     */
    public function getSuppressionId()
    {
        return [
            'method' => 'GET',
            'path'   => 'suppressions/{suppressionId}',
            'params' => [
                'suppressionId' => $this->params->suppressionIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /suppressions/{suppressionId} HTTP operation
     *
     * @return array
     */
    public function deleteSuppressionId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'suppressions/{suppressionId}',
            'params' => [
                'suppressionId' => $this->params->suppressionIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/connections HTTP operation
     *
     * @return array
     */
    public function getConnections()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/connections',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /entities/{entityId}/test-check HTTP operation
     *
     * @return array
     */
    public function postTestcheck()
    {
        return [
            'method' => 'POST',
            'path'   => 'entities/{entityId}/test-check',
            'params' => [
                'entityId'            => $this->params->entityIdUrl(),
                'details'             => $this->params->detailsJson(),
                'method'              => $this->params->methodJson(),
                'monitoringZonesPoll' => $this->params->monitoringZonesPollJson(),
                'targetAlias'         => $this->params->targetAliasJson(),
                'timeout'             => $this->params->timeoutJson(),
                'type'                => $this->params->typeJson(),
                'url'                 => $this->params->urlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/who HTTP operation
     *
     * @return array
     */
    public function getWho()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/who',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /entities/{entityId}/test-alarm HTTP operation
     *
     * @return array
     */
    public function postTestalarm()
    {
        return [
            'method' => 'POST',
            'path'   => 'entities/{entityId}/test-alarm',
            'params' => [
                'entityId'         => $this->params->entityIdUrl(),
                'available'        => $this->params->availableJson(),
                'bytes'            => $this->params->bytesJson(),
                'checkData'        => $this->params->checkDataJson(),
                'code'             => $this->params->codeJson(),
                'criteria'         => $this->params->criteriaJson(),
                'data'             => $this->params->dataJson(),
                'duration'         => $this->params->durationJson(),
                'metrics'          => $this->params->metricsJson(),
                'monitoringZoneId' => $this->params->monitoringZoneIdJson(),
                'status'           => $this->params->statusJson(),
                'timestamp'        => $this->params->timestampJson(),
                'ttConnect'        => $this->params->ttConnectJson(),
                'ttFirstbyte'      => $this->params->ttFirstbyteJson(),
                'type'             => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /notifications/{notificationId} HTTP operation
     *
     * @return array
     */
    public function deleteNotificationId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'notifications/{notificationId}',
            'params' => [
                'notificationId' => $this->params->notificationIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /notifications/{notificationId} HTTP operation
     *
     * @return array
     */
    public function putNotificationId()
    {
        return [
            'method' => 'PUT',
            'path'   => 'notifications/{notificationId}',
            'params' => [
                'notificationId' => $this->params->notificationIdUrl(),
                'details'        => $this->params->detailsJson(),
                'type'           => $this->params->typeJson(),
                'url'            => $this->params->urlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /notifications/{notificationId} HTTP operation
     *
     * @return array
     */
    public function getNotificationId()
    {
        return [
            'method' => 'GET',
            'path'   => 'notifications/{notificationId}',
            'params' => [
                'notificationId' => $this->params->notificationIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/cpus HTTP operation
     *
     * @return array
     */
    public function getCpus()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/cpus',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /alarm_examples/{alarmExampleId} HTTP operation
     *
     * @return array
     */
    public function getAlarmExampleId()
    {
        return [
            'method' => 'GET',
            'path'   => 'alarm_examples/{alarmExampleId}',
            'params' => [
                'alarmExampleId' => $this->params->alarmExampleIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /alarm_examples/{alarmExampleId} HTTP operation
     *
     * @return array
     */
    public function postAlarmExampleId()
    {
        return [
            'method' => 'POST',
            'path'   => 'alarm_examples/{alarmExampleId}',
            'params' => [
                'alarmExampleId' => $this->params->alarmExampleIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info_types HTTP operation
     *
     * @return array
     */
    public function getHostInfoTypes()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info_types',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/disks HTTP operation
     *
     * @return array
     */
    public function getDisks()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/disks',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/system HTTP operation
     *
     * @return array
     */
    public function getSystem()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/system',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/memory HTTP operation
     *
     * @return array
     */
    public function getMemory()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/memory',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /notifications/{notificationId}/test HTTP
     * operation
     *
     * @return array
     */
    public function postTest()
    {
        return [
            'method' => 'POST',
            'path'   => 'notifications/{notificationId}/test',
            'params' => [
                'notificationId' => $this->params->notificationIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /monitoring_zones/{monitoringZoneId} HTTP
     * operation
     *
     * @return array
     */
    public function getMonitoringZoneId()
    {
        return [
            'method' => 'GET',
            'path'   => 'monitoring_zones/{monitoringZoneId}',
            'params' => [
                'monitoringZoneId' => $this->params->monitoringZoneIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/processes HTTP
     * operation
     *
     * @return array
     */
    public function getProcesses()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/processes',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /entities/{entityId}/checks/{checkId} HTTP
     * operation
     *
     * @return array
     */
    public function getCheckId()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/checks/{checkId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'checkId'  => $this->params->checkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /entities/{entityId}/alarms/{alarmId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteAlarmId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'entities/{entityId}/alarms/{alarmId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'alarmId'  => $this->params->alarmIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /entities/{entityId}/checks/{checkId} HTTP
     * operation
     *
     * @return array
     */
    public function putCheckId()
    {
        return [
            'method' => 'PUT',
            'path'   => 'entities/{entityId}/checks/{checkId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'checkId'  => $this->params->checkIdUrl(),
                'label'    => $this->params->labelJson(),
                'timeout'  => $this->params->timeoutJson(),
            ],
        ];
    }

    /**
     * Returns information about PUT /entities/{entityId}/alarms/{alarmId} HTTP
     * operation
     *
     * @return array
     */
    public function putAlarmId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'entities/{entityId}/alarms/{alarmId}',
            'jsonKey' => 'criteria',
            'params'  => [
                'entityId' => $this->params->entityIdUrl(),
                'alarmId'  => $this->params->alarmIdUrl(),
                'criteria' => $this->params->criteriaJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /entities/{entityId}/checks/{checkId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteCheckId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'entities/{entityId}/checks/{checkId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'checkId'  => $this->params->checkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /entities/{entityId}/alarms/{alarmId} HTTP
     * operation
     *
     * @return array
     */
    public function getAlarmId()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/alarms/{alarmId}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'alarmId'  => $this->params->alarmIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/connections/{connId} HTTP
     * operation
     *
     * @return array
     */
    public function getConnId()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/connections/{connId}',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
                'connId'  => $this->params->connIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /views/alarmsByNp/{notificationPlanId} HTTP
     * operation
     *
     * @return array
     */
    public function getNotificationPlanId()
    {
        return [
            'method' => 'GET',
            'path'   => 'views/alarmsByNp/{notificationPlanId}',
            'params' => [
                'notificationPlanId' => $this->params->notificationPlanIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/filesystems HTTP
     * operation
     *
     * @return array
     */
    public function getFilesystems()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/filesystems',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /notification_plans/{notificationPlanId} HTTP
     * operation
     *
     * @return array
     */
    public function putNotificationPlanId()
    {
        return [
            'method' => 'PUT',
            'path'   => 'notification_plans/{notificationPlanId}',
            'params' => [
                'notificationPlanId' => $this->params->notificationPlanIdUrl(),
                'criticalState'      => $this->params->criticalStateJson(),
                'warningState'       => $this->params->warningStateJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /notification_types/{notificationTypeId} HTTP
     * operation
     *
     * @return array
     */
    public function getNotificationTypeId()
    {
        return [
            'method' => 'GET',
            'path'   => 'notification_types/{notificationTypeId}',
            'params' => [
                'notificationTypeId' => $this->params->notificationTypeIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /notification_plans/{notificationPlanId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteNotificationPlanId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'notification_plans/{notificationPlanId}',
            'params' => [
                'notificationPlanId' => $this->params->notificationPlanIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /entities/{entityId}/checks/{checkId}/metrics HTTP
     * operation
     *
     * @return array
     */
    public function getMetrics()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/checks/{checkId}/metrics',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'checkId'  => $this->params->checkIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /agents/{agentId}/host_info/network_interaces HTTP
     * operation
     *
     * @return array
     */
    public function getNetworkInteraces()
    {
        return [
            'method' => 'GET',
            'path'   => 'agents/{agentId}/host_info/network_interaces',
            'params' => [
                'agentId' => $this->params->agentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /monitoring_zones/{monitoringZoneId}/traceroute
     * HTTP operation
     *
     * @return array
     */
    public function postTraceroute()
    {
        return [
            'method' => 'POST',
            'path'   => 'monitoring_zones/{monitoringZoneId}/traceroute',
            'params' => [
                'monitoringZoneId' => $this->params->monitoringZoneIdUrl(),
                'target'           => $this->params->targetJson(),
                'targetResolver'   => $this->params->targetResolverJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /entities/{entityId}/alarms/{alarmId}/notification_history HTTP operation
     *
     * @return array
     */
    public function getNotificationHistory()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/alarms/{alarmId}/notification_history',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'alarmId'  => $this->params->alarmIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /entities/{entityId}/checks/{checkId}/metrics/{metricName}/plot HTTP operation
     *
     * @return array
     */
    public function getPlot()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/checks/{checkId}/metrics/{metricName}/plot',
            'params' => [
                'entityId'   => $this->params->entityIdUrl(),
                'checkId'    => $this->params->checkIdUrl(),
                'metricName' => $this->params->metricNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /entities/{entityId}/agent/check_types/{agentCheckType}/targets HTTP operation
     *
     * @return array
     */
    public function getTargets()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/agent/check_types/{agentCheckType}/targets',
            'params' => [
                'entityId'       => $this->params->entityIdUrl(),
                'agentCheckType' => $this->params->agentCheckTypeUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /entities/{entityId}/alarms/{alarmId}/notification_history/{checkId}/{uuid} HTTP
     * operation
     *
     * @return array
     */
    public function getUuid()
    {
        return [
            'method' => 'GET',
            'path'   => 'entities/{entityId}/alarms/{alarmId}/notification_history/{checkId}/{uuid}',
            'params' => [
                'entityId' => $this->params->entityIdUrl(),
                'alarmId'  => $this->params->alarmIdUrl(),
                'checkId'  => $this->params->checkIdUrl(),
                'uuid'     => $this->params->uuidUrl(),
            ],
        ];
    }
}
