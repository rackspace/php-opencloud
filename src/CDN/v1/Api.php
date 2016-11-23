<?php declare(strict_types=1);

namespace Rackspace\CDN\v1;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET {project_id}/ HTTP operation
     *
     * @return array
     */
    public function getProject()
    {
        return [
            'method' => 'GET',
            'path'   => '{project_id}',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET {project_id}/ping HTTP operation
     *
     * @return array
     */
    public function getPing()
    {
        return [
            'method' => 'GET',
            'path'   => '{project_id}/ping',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET {project_id}/flavors HTTP operation
     *
     * @return array
     */
    public function getFlavors()
    {
        return [
            'method' => 'GET',
            'path'   => '{project_id}/flavors',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST {project_id}/services HTTP operation
     *
     * @return array
     */
    public function postServices()
    {
        return [
            'method' => 'POST',
            'path'   => '{project_id}/services',
            'params' => [
                'projectId'    => $this->params->projectIdUrl(),
                'caching'      => $this->params->cachingJson(),
                'domains'      => $this->params->domainsJson(),
                'flavorId'     => $this->params->flavorIdJson(),
                'logDelivery'  => $this->params->logDeliveryJson(),
                'name'         => $this->params->nameJson(),
                'origins'      => $this->params->originsJson(),
                'restrictions' => $this->params->restrictionsJson(),
            ],
        ];
    }

    /**
     * Returns information about GET {project_id}/services HTTP operation
     *
     * @return array
     */
    public function getServices()
    {
        return [
            'method' => 'GET',
            'path'   => '{project_id}/services',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST {project_id}/ssl_certificate HTTP operation
     *
     * @return array
     */
    public function postSslCertificate()
    {
        return [
            'method' => 'POST',
            'path'   => '{project_id}/ssl_certificate',
            'params' => [
                'projectId'  => $this->params->projectIdUrl(),
                'certType'   => $this->params->certTypeJson(),
                'domainName' => $this->params->domainNameJson(),
                'flavorId'   => $this->params->flavorIdJson(),
            ],
        ];
    }

    /**
     * Returns information about GET {project_id}/flavors/{flavor_id} HTTP
     * operation
     *
     * @return array
     */
    public function getFlavor()
    {
        return [
            'method' => 'GET',
            'path'   => '{project_id}/flavors/{flavor_id}',
            'params' => [
                'projectId'         => $this->params->projectIdUrl(),
                'flavorId'          => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PATCH {project_id}/services/{service_id} HTTP
     * operation
     *
     * @return array
     */
    public function patchService()
    {
        return [
            'method' => 'PATCH',
            'path'   => '{project_id}/services/{service_id}',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
                'serviceId' => $this->params->serviceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET {project_id}/services/{service_id} HTTP
     * operation
     *
     * @return array
     */
    public function getService()
    {
        return [
            'method' => 'GET',
            'path'   => '{project_id}/services/{service_id}',
            'params' => [
                'projectId'                 => $this->params->projectIdUrl(),
                'serviceId'                 => $this->params->serviceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE {project_id}/services/{service_id} HTTP
     * operation
     *
     * @return array
     */
    public function deleteService()
    {
        return [
            'method' => 'DELETE',
            'path'   => '{project_id}/services/{service_id}',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
                'serviceId' => $this->params->serviceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE {project_id}/services/{service_id}/assets
     * HTTP operation
     *
     * @return array
     */
    public function deleteAssets()
    {
        return [
            'method' => 'DELETE',
            'path'   => '{project_id}/services/{service_id}/assets',
            'params' => [
                'projectId' => $this->params->projectIdUrl(),
                'serviceId' => $this->params->serviceIdUrl(),
                'hard'      => $this->params->hardJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * {project_id}/ssl_certificate/{domain_name} HTTP operation
     *
     * @return array
     */
    public function deleteDomain()
    {
        return [
            'method' => 'DELETE',
            'path'   => '{project_id}/ssl_certificate/{domain_name}',
            'params' => [
                'projectId'  => $this->params->projectIdUrl(),
                'domainName' => $this->params->domainNameUrl(),
            ],
        ];
    }
}
