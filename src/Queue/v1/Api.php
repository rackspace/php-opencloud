<?php declare(strict_types=1);

namespace Rackspace\Queue\v1;

use OpenCloud\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET /v1/{project_id}/queues HTTP operation
     *
     * @return array
     */
    public function getQueues()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues',
            'params' => [
                'detailed' => $this->params->detailedJson(),
                'limit'    => $this->params->limitJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v1/{project_id}/queues/{queue_name} HTTP
     * operation
     *
     * @return array
     */
    public function deleteQueueName()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v1/{project_id}/queues/{queue_name}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v1/{project_id}/queues/{queue_name} HTTP
     * operation
     *
     * @return array
     */
    public function getQueueName()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues/{queue_name}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /v1/{project_id}/queues/{queue_name} HTTP
     * operation
     *
     * @return array
     */
    public function putQueueName()
    {
        return [
            'method' => 'PUT',
            'path'   => '/v1/{project_id}/queues/{queue_name}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v1/{project_id}/queues/{queue_name}/stats HTTP
     * operation
     *
     * @return array
     */
    public function getStats()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues/{queue_name}/stats',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /v1/{project_id}/queues/{queue_name}/claims HTTP
     * operation
     *
     * @return array
     */
    public function postClaims()
    {
        return [
            'method' => 'POST',
            'path'   => '/v1/{project_id}/queues/{queue_name}/claims',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                'grace'     => $this->params->graceJson(),
                'ttl'       => $this->params->ttlJson(),
            ],
        ];
    }

    /**
     * Returns information about POST /v1/{project_id}/queues/{queue_name}/messages
     * HTTP operation
     *
     * @return array
     */
    public function postMessages()
    {
        return [
            'method' => 'POST',
            'path'   => '/v1/{project_id}/queues/{queue_name}/messages',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                ''          => $this->params->Json(),
                'body'      => $this->params->bodyJson(),
                'event'     => $this->params->eventJson(),
                'ttl'       => $this->params->ttlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /v1/{project_id}/queues/{queue_name}/metadata HTTP
     * operation
     *
     * @return array
     */
    public function getMetadata()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues/{queue_name}/metadata',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /v1/{project_id}/queues/{queue_name}/messages HTTP
     * operation
     *
     * @return array
     */
    public function getMessages()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues/{queue_name}/messages',
            'params' => [
                'queueName'      => $this->params->queueNameUrl(),
                'echo'           => $this->params->echoJson(),
                'includeClaimed' => $this->params->includeClaimedJson(),
                'limit'          => $this->params->limitJson(),
            ],
        ];
    }

    /**
     * Returns information about PUT /v1/{project_id}/queues/{queue_name}/metadata HTTP
     * operation
     *
     * @return array
     */
    public function putMetadata()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/v1/{project_id}/queues/{queue_name}/metadata',
            'jsonKey' => 'new metadata',
            'params'  => [
                'queueName' => $this->params->queueNameUrl(),
                'metadata'  => $this->params->metadata(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /v1/{project_id}/queues/{queue_name}/messages
     * HTTP operation
     *
     * @return array
     */
    public function deleteMessages()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v1/{project_id}/queues/{queue_name}/messages',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /v1/{project_id}/queues/{queue_name}/claims/{claimId} HTTP operation
     *
     * @return array
     */
    public function getClaimId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues/{queue_name}/claims/{claimId}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                'claimId'   => $this->params->claimIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PATCH
     * /v1/{project_id}/queues/{queue_name}/claims/{claimId} HTTP operation
     *
     * @return array
     */
    public function patchClaimId()
    {
        return [
            'method' => 'PATCH',
            'path'   => '/v1/{project_id}/queues/{queue_name}/claims/{claimId}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                'claimId'   => $this->params->claimIdUrl(),
                'grace'     => $this->params->graceJson(),
                'ttl'       => $this->params->ttlJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /v1/{project_id}/queues/{queue_name}/claims/{claimId} HTTP operation
     *
     * @return array
     */
    public function deleteClaimId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v1/{project_id}/queues/{queue_name}/claims/{claimId}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                'claimId'   => $this->params->claimIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /v1/{project_id}/queues/{queue_name}/messages/{messageId} HTTP operation
     *
     * @return array
     */
    public function getMessageId()
    {
        return [
            'method' => 'GET',
            'path'   => '/v1/{project_id}/queues/{queue_name}/messages/{messageId}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                'messageId' => $this->params->messageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /v1/{project_id}/queues/{queue_name}/messages/{messageId} HTTP operation
     *
     * @return array
     */
    public function deleteMessageId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/v1/{project_id}/queues/{queue_name}/messages/{messageId}',
            'params' => [
                'queueName' => $this->params->queueNameUrl(),
                'messageId' => $this->params->messageIdUrl(),
            ],
        ];
    }
}