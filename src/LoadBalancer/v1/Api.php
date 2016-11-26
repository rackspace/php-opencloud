<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET loadbalancers HTTP operation
     *
     * @return array
     */
    public function getLoadbalancers()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers',
            'params' => [],
        ];
    }

    /**
     * Returns information about DELETE loadbalancers HTTP operation
     *
     * @return array
     */
    public function deleteLoadbalancers()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST loadbalancers HTTP operation
     *
     * @return array
     */
    public function postLoadbalancers()
    {
        return [
            'method'  => 'POST',
            'path'    => 'loadbalancers',
            'jsonKey' => 'loadBalancer',
            'params'  => [
                'accessList'         => $this->params->accessListJson(),
                'address'            => $this->params->addressJson(),
                'algorithm'          => $this->params->algorithmJson(),
                'condition'          => $this->params->conditionJson(),
                'connectionLogging'  => $this->params->connectionLoggingJson(),
                'connectionThrottle' => $this->params->connectionThrottleJson(),
                'halfClosed'         => $this->params->halfClosedJson(),
                'healthMonitor'      => $this->params->healthMonitorJson(),
                'httpsRedirect'      => $this->params->httpsRedirectJson(),
                'id'                 => $this->params->idJson(),
                'metadata'           => $this->params->metadataJson(),
                'name'               => $this->params->nameJson(),
                'nodes'              => $this->params->nodesJson(),
                'port'               => $this->params->portJson(),
                'protocol'           => $this->params->protocolJson(),
                'sessionPersistence' => $this->params->sessionPersistenceJson(),
                'timeout'            => $this->params->timeoutJson(),
                'type'               => $this->params->typeJson(),
                'virtualIps'         => $this->params->virtualIpsJson(),
            ],
        ];
    }

    /**
     * Returns information about GET loadbalancers/usage HTTP operation
     *
     * @return array
     */
    public function getUsage()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/usage',
            'params' => []
        ];
    }

    /**
     * Returns information about GET loadbalancers/billable HTTP
     * operation
     *
     * @return array
     */
    public function getBillable()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/billable',
            'params' => []
        ];
    }

    /**
     * Returns information about GET loadbalancers/protocols HTTP
     * operation
     *
     * @return array
     */
    public function getProtocols()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/protocols',
            'params' => []
        ];
    }

    /**
     * Returns information about GET loadbalancers/algorithms HTTP
     * operation
     *
     * @return array
     */
    public function getAlgorithms()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/algorithms',
            'params' => []
        ];
    }

    /**
     * Returns information about GET loadbalancers/alloweddomains HTTP
     * operation
     *
     * @return array
     */
    public function getAlloweddomains()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/alloweddomains',
            'params' => []
        ];
    }

    /**
     * Returns information about GET loadbalancers/{loadBalancerId}
     * HTTP operation
     *
     * @return array
     */
    public function getLoadBalancerId()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}',
            'params' => [

                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT loadbalancers/{loadBalancerId}
     * HTTP operation
     *
     * @return array
     */
    public function putLoadBalancerId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}',
            'jsonKey' => 'loadBalancer',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'algorithm'      => $this->params->algorithmJson(),
                'halfClosed'     => $this->params->halfClosedJson(),
                'httpsRedirect'  => $this->params->httpsRedirectJson(),
                'name'           => $this->params->nameJson(),
                'port'           => $this->params->portJson(),
                'protocol'       => $this->params->protocolJson(),
                'timeout'        => $this->params->timeoutJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE loadbalancers/{loadBalancerId}
     * HTTP operation
     *
     * @return array
     */
    public function deleteLoadBalancerId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * loadbalancers/{loadBalancerId}/nodes HTTP operation
     *
     * @return array
     */
    public function postNodes()
    {
        return [
            'method'  => 'POST',
            'path'    => 'loadbalancers/{loadBalancerId}/nodes',
            'jsonKey' => 'nodes',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'address'        => $this->params->addressJson(),
                'condition'      => $this->params->conditionJson(),
                'nodes'          => $this->params->nodesJson(),
                'port'           => $this->params->portJson(),
                'type'           => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/nodes HTTP operation
     *
     * @return array
     */
    public function getNodes()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/nodes',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/nodes HTTP operation
     *
     * @return array
     */
    public function deleteNodes()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/nodes',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/stats HTTP operation
     *
     * @return array
     */
    public function getStats()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/stats',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * loadbalancers/{loadBalancerId}/metadata HTTP operation
     *
     * @return array
     */
    public function postMetadata()
    {
        return [
            'method'  => 'POST',
            'path'    => 'loadbalancers/{loadBalancerId}/metadata',
            'jsonKey' => 'meta',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'value'          => $this->params->valueJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/metadata HTTP operation
     *
     * @return array
     */
    public function getMetadata()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/metadata',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/metadata HTTP operation
     *
     * @return array
     */
    public function deleteMetadata()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/metadata',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/errorpage HTTP operation
     *
     * @return array
     */
    public function deleteErrorpage()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/errorpage',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/errorpage HTTP operation
     *
     * @return array
     */
    public function putErrorpage()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/errorpage',
            'jsonKey' => 'errorpage',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'content'        => $this->params->contentJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/errorpage HTTP operation
     *
     * @return array
     */
    public function getErrorpage()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/errorpage',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/accesslist HTTP operation
     *
     * @return array
     */
    public function getAccesslist()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/accesslist',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/accesslist HTTP operation
     *
     * @return array
     */
    public function deleteAccesslist()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/accesslist',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * loadbalancers/{loadBalancerId}/virtualips HTTP operation
     *
     * @return array
     */
    public function postVirtualips()
    {
        return [
            'method'  => 'POST',
            'path'    => 'loadbalancers/{loadBalancerId}/virtualips',
            'jsonKey' => 'id',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'id'             => $this->params->idJson(),
                'ipVersion'      => $this->params->ipVersionJson(),
                'type'           => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * loadbalancers/{loadBalancerId}/accesslist HTTP operation
     *
     * @return array
     */
    public function postAccesslist()
    {
        return [
            'method'  => 'POST',
            'path'    => 'loadbalancers/{loadBalancerId}/accesslist',
            'jsonKey' => 'accessList',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'accessList'     => $this->params->accessListJson(),
                'address'        => $this->params->addressJson(),
                'type'           => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/virtualips HTTP operation
     *
     * @return array
     */
    public function getVirtualips()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/virtualips',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/virtualips HTTP operation
     *
     * @return array
     */
    public function deleteVirtualips()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/virtualips',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/nodes/events HTTP operation
     *
     * @return array
     */
    public function getEvents()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/nodes/events',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/healthmonitor HTTP operation
     *
     * @return array
     */
    public function getHealthmonitor()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/healthmonitor',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/usage/current HTTP operation
     *
     * @return array
     */
    public function getCurrent()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/usage/current',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/healthmonitor HTTP operation
     *
     * @return array
     */
    public function putHealthmonitor()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/healthmonitor',
            'jsonKey' => 'healthMonitor',
            'params'  => [
                'loadBalancerId'             => $this->params->loadBalancerIdUrl(),
                'attemptsBeforeDeactivation' => $this->params->attemptsBeforeDeactivationJson(),
                'bodyRegex'                  => $this->params->bodyRegexJson(),
                'delay'                      => $this->params->delayJson(),
                'hostHeader'                 => $this->params->hostHeaderJson(),
                'path'                       => $this->params->pathJson(),
                'statusRegex'                => $this->params->statusRegexJson(),
                'timeout'                    => $this->params->timeoutJson(),
                'type'                       => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/healthmonitor HTTP operation
     *
     * @return array
     */
    public function deleteHealthmonitor()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/healthmonitor',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/nodes/{nodeId} HTTP operation
     *
     * @return array
     */
    public function getNodeId()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/nodes/{nodeId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'nodeId'         => $this->params->nodeIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/ssltermination HTTP operation
     *
     * @return array
     */
    public function putSsltermination()
    {
        return [
            'method' => 'PUT',
            'path'   => 'loadbalancers/{loadBalancerId}/ssltermination',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/ssltermination HTTP operation
     *
     * @return array
     */
    public function getSsltermination()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/ssltermination',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/nodes/{nodeId} HTTP operation
     *
     * @return array
     */
    public function putNodeId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/nodes/{nodeId}',
            'jsonKey' => 'node',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'nodeId'         => $this->params->nodeIdUrl(),
                'condition'      => $this->params->conditionJson(),
                'weight'         => $this->params->weightJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/nodes/{nodeId} HTTP operation
     *
     * @return array
     */
    public function deleteNodeId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/nodes/{nodeId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'nodeId'         => $this->params->nodeIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/contentcaching HTTP operation
     *
     * @return array
     */
    public function putContentcaching()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/contentcaching',
            'jsonKey' => 'contentCaching',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'enabled'        => $this->params->enabledJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/contentcaching HTTP operation
     *
     * @return array
     */
    public function getContentcaching()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/contentcaching',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/ssltermination HTTP operation
     *
     * @return array
     */
    public function deleteSsltermination()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/ssltermination',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/connectionlogging HTTP operation
     *
     * @return array
     */
    public function getConnectionlogging()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/connectionlogging',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/connectionlogging HTTP operation
     *
     * @return array
     */
    public function putConnectionlogging()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/connectionlogging',
            'jsonKey' => 'connectionLogging',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'enabled'        => $this->params->enabledJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/metadata/{metaId} HTTP operation
     *
     * @return array
     */
    public function deleteMetaId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/metadata/{metaId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'metaId'         => $this->params->metaIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/metadata/{metaId} HTTP operation
     *
     * @return array
     */
    public function putMetaId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/metadata/{metaId}',
            'jsonKey' => 'meta',
            'params'  => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'metaId'         => $this->params->metaIdUrl(),
                'value'          => $this->params->valueJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/metadata/{metaId} HTTP operation
     *
     * @return array
     */
    public function getMetaId()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/metadata/{metaId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'metaId'         => $this->params->metaIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/connectionthrottle HTTP operation
     *
     * @return array
     */
    public function putConnectionthrottle()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/connectionthrottle',
            'jsonKey' => 'connectionThrottle',
            'params'  => [
                'loadBalancerId'    => $this->params->loadBalancerIdUrl(),
                'maxConnectionRate' => $this->params->maxConnectionRateJson(),
                'maxConnections'    => $this->params->maxConnectionsJson(),
                'minConnections'    => $this->params->minConnectionsJson(),
                'rateInterval'      => $this->params->rateIntervalJson(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/sessionpersistence HTTP operation
     *
     * @return array
     */
    public function putSessionpersistence()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/sessionpersistence',
            'jsonKey' => 'sessionPersistence',
            'params'  => [
                'loadBalancerId'  => $this->params->loadBalancerIdUrl(),
                'persistenceType' => $this->params->persistenceTypeJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/connectionthrottle HTTP operation
     *
     * @return array
     */
    public function getConnectionthrottle()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/connectionthrottle',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/connectionthrottle HTTP operation
     *
     * @return array
     */
    public function deleteConnectionthrottle()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/connectionthrottle',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/sessionpersistence HTTP operation
     *
     * @return array
     */
    public function getSessionpersistence()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/sessionpersistence',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/sessionpersistence HTTP operation
     *
     * @return array
     */
    public function deleteSessionpersistence()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/sessionpersistence',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/virtualips/{virtualIpId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteVirtualIpId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/virtualips/{virtualIpId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'virtualIpId'    => $this->params->virtualIpIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/accesslist/{networkItemId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteNetworkItemId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/accesslist/{networkItemId}',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
                'networkItemId'  => $this->params->networkItemIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * loadbalancers/{loadBalancerId}/ssltermination/certificatemappings
     * HTTP operation
     *
     * @return array
     */
    public function postCertificatemappings()
    {
        return [
            'method'  => 'POST',
            'path'    => 'loadbalancers/{loadBalancerId}/ssltermination/certificatemappings',
            'jsonKey' => 'certificateMapping',
            'params'  => [
                'loadBalancerId'          => $this->params->loadBalancerIdUrl(),
                'certificate'             => $this->params->certificateJson(),
                'hostName'                => $this->params->hostNameJson(),
                'intermediateCertificate' => $this->params->intermediateCertificateJson(),
                'privateKey'              => $this->params->privateKeyJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/ssltermination/certificatemappings
     * HTTP operation
     *
     * @return array
     */
    public function getCertificatemappings()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/ssltermination/certificatemappings',
            'params' => [
                'loadBalancerId' => $this->params->loadBalancerIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * loadbalancers/{loadBalancerId}/ssltermination/certificatemappings/{certificateMappingId}
     * HTTP operation
     *
     * @return array
     */
    public function deleteCertificateMappingId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'loadbalancers/{loadBalancerId}/ssltermination/certificatemappings/{certificateMappingId}',
            'params' => [
                'loadBalancerId'       => $this->params->loadBalancerIdUrl(),
                'certificateMappingId' => $this->params->certificateMappingIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * loadbalancers/{loadBalancerId}/ssltermination/certificatemappings/{certificateMappingId}
     * HTTP operation
     *
     * @return array
     */
    public function putCertificateMappingId()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'loadbalancers/{loadBalancerId}/ssltermination/certificatemappings/{certificateMappingId}',
            'jsonKey' => 'certificateMapping',
            'params'  => [
                'loadBalancerId'          => $this->params->loadBalancerIdUrl(),
                'certificateMappingId'    => $this->params->certificateMappingIdUrl(),
                'certificate'             => $this->params->certificateJson(),
                'hostName'                => $this->params->hostNameJson(),
                'intermediateCertificate' => $this->params->intermediateCertificateJson(),
                'privateKey'              => $this->params->privateKeyJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * loadbalancers/{loadBalancerId}/ssltermination/certificatemappings/{certificateMappingId}
     * HTTP operation
     *
     * @return array
     */
    public function getCertificateMappingId()
    {
        return [
            'method' => 'GET',
            'path'   => 'loadbalancers/{loadBalancerId}/ssltermination/certificatemappings/{certificateMappingId}',
            'params' => [
                'loadBalancerId'       => $this->params->loadBalancerIdUrl(),
                'certificateMappingId' => $this->params->certificateMappingIdUrl(),
            ],
        ];
    }
}
