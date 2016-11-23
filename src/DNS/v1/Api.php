<?php declare(strict_types=1);

namespace Rackspace\DNS\v1;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about POST rdns HTTP operation
     *
     * @return array
     */
    public function postRdns()
    {
        return [
            'method' => 'POST',
            'path'   => 'rdns',
            'params' => [],
        ];
    }

    /**
     * Returns information about PUT rdns HTTP operation
     *
     * @return array
     */
    public function putRdns()
    {
        return [
            'method' => 'PUT',
            'path'   => 'rdns',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET limits HTTP operation
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
     * Returns information about GET domains HTTP operation
     *
     * @return array
     */
    public function getDomains()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST domains HTTP operation
     *
     * @return array
     */
    public function postDomains()
    {
        return [
            'method'  => 'POST',
            'path'    => 'domains',
            'jsonKey' => 'domains',
            'params'  => [
                'domains' => $this->params->domainsJson(),
            ],
        ];
    }

    /**
     * Returns information about PUT domains HTTP operation
     *
     * @return array
     */
    public function putDomains()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'domains',
            'jsonKey' => 'domains',
            'params'  => [
                'comment'      => $this->params->commentJson(),
                'domains'      => $this->params->domainsJson(),
                'emailAddress' => $this->params->emailAddressJson(),
                'id'           => $this->params->idJson(),
                'ttl'          => $this->params->ttlJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE domains HTTP operation
     *
     * @return array
     */
    public function deleteDomains()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'domains',
            'params' => [
                'deleteSubdomains' => $this->params->deleteSubdomainsJson(),
            ],
        ];
    }

    /**
     * Returns information about GET limits/types HTTP operation
     *
     * @return array
     */
    public function getTypes()
    {
        return [
            'method' => 'GET',
            'path'   => 'limits/types',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET limits/{type} HTTP operation
     *
     * @return array
     */
    public function getType()
    {
        return [
            'method' => 'GET',
            'path'   => 'limits/{type}',
            'params' => [
                'type' => $this->params->typeUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST domains/import HTTP operation
     *
     * @return array
     */
    public function postImport()
    {
        return [
            'method'  => 'POST',
            'path'    => 'domains/import',
            'jsonKey' => 'domains',
            'params'  => [
                'domains' => $this->params->domainsJson(),
            ],
        ];
    }

    /**
     * Returns information about GET domains/search HTTP operation
     *
     * @return array
     */
    public function getSearch()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/search',
            'params' => [],
        ];
    }

    /**
     * Returns information about PUT domains/{domainId} HTTP operation
     *
     * @return array
     */
    public function putDomainId()
    {
        return [
            'method' => 'PUT',
            'path'   => 'domains/{domainId}',
            'params' => [
                'domainId'     => $this->params->domainIdUrl(),
                'comment'      => $this->params->commentJson(),
                'emailAddress' => $this->params->emailAddressJson(),
                'ttl'          => $this->params->ttlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET domains/{domainId} HTTP operation
     *
     * @return array
     */
    public function getDomainId()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/{domainId}',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE domains/{domainId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteDomainId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'domains/{domainId}',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST domains/{domainId}/clone HTTP
     * operation
     *
     * @return array
     */
    public function postClone()
    {
        return [
            'method' => 'POST',
            'path'   => 'domains/{domainId}/clone',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET domains/{domainId}/export HTTP
     * operation
     *
     * @return array
     */
    public function getExport()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/{domainId}/export',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE domains/{domainId}/records HTTP
     * operation
     *
     * @return array
     */
    public function deleteRecords()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'domains/{domainId}/records',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT domains/{domainId}/records HTTP
     * operation
     *
     * @return array
     */
    public function putRecords()
    {
        return [
            'method'  => 'PUT',
            'path'    => 'domains/{domainId}/records',
            'jsonKey' => 'records',
            'params'  => [
                'domainId' => $this->params->domainIdUrl(),
                'records'  => $this->params->recordsJson(),
            ],
        ];
    }

    /**
     * Returns information about POST domains/{domainId}/records HTTP
     * operation
     *
     * @return array
     */
    public function postRecords()
    {
        return [
            'method'  => 'POST',
            'path'    => 'domains/{domainId}/records',
            'jsonKey' => 'records',
            'params'  => [
                'domainId' => $this->params->domainIdUrl(),
                'records'  => $this->params->recordsJson(),
            ],
        ];
    }

    /**
     * Returns information about GET domains/{domainId}/changes HTTP
     * operation
     *
     * @return array
     */
    public function getChanges()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/{domainId}/changes',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET domains/{domainId}/records HTTP
     * operation
     *
     * @return array
     */
    public function getRecords()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/{domainId}/records',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET domains/{domainId}/subdomains HTTP
     * operation
     *
     * @return array
     */
    public function getSubdomains()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/{domainId}/subdomains',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * domains/{domainId}/records/{recordId} HTTP operation
     *
     * @return array
     */
    public function putRecordId()
    {
        return [
            'method' => 'PUT',
            'path'   => 'domains/{domainId}/records/{recordId}',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
                'recordId' => $this->params->recordIdUrl(),
                'comment'  => $this->params->commentJson(),
                'data'     => $this->params->dataJson(),
                'name'     => $this->params->nameJson(),
                'priority' => $this->params->priorityJson(),
                'ttl'      => $this->params->ttlJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * domains/{domainId}/records/{recordId} HTTP operation
     *
     * @return array
     */
    public function deleteRecordId()
    {
        return [
            'method' => 'DELETE',
            'path'   => 'domains/{domainId}/records/{recordId}',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
                'recordId' => $this->params->recordIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * domains/{domainId}/records/{recordId} HTTP operation
     *
     * @return array
     */
    public function getRecordId()
    {
        return [
            'method' => 'GET',
            'path'   => 'domains/{domainId}/records/{recordId}',
            'params' => [
                'domainId' => $this->params->domainIdUrl(),
                'recordId' => $this->params->recordIdUrl(),
            ],
        ];
    }
}
