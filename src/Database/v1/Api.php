<?php declare(strict_types=1);

namespace Rackspace\Database\v1;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET / HTTP operation
     *
     * @return array
     */
    public function get()
    {
        return [
            'method' => 'GET',
            'path'   => '/',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /{version} HTTP operation
     *
     * @return array
     */
    public function getVersion()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}',
            'params' => [
                'version' => $this->params->versionUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/ha HTTP operation
     *
     * @return array
     */
    public function getHa()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/ha',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/ha HTTP operation
     *
     * @return array
     */
    public function postHa()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/ha',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/backups HTTP operation
     *
     * @return array
     */
    public function postBackups()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/backups',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/backups HTTP operation
     *
     * @return array
     */
    public function getBackups()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/backups',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/flavors HTTP operation
     *
     * @return array
     */
    public function getFlavors()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/flavors',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/ha/{haId} HTTP operation
     *
     * @return array
     */
    public function getHaId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/ha/{haId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'haId'      => $this->params->haIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/instances HTTP operation
     *
     * @return array
     */
    public function getInstances()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/instances HTTP operation
     *
     * @return array
     */
    public function postInstances()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/instances',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/schedules HTTP operation
     *
     * @return array
     */
    public function postSchedules()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/schedules',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /{version}/{accountId}/ha/{haId} HTTP operation
     *
     * @return array
     */
    public function deleteHaId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/ha/{haId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'haId'      => $this->params->haIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/schedules HTTP operation
     *
     * @return array
     */
    public function getSchedules()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/schedules',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/datastores HTTP operation
     *
     * @return array
     */
    public function getDatastores()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/datastores',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/configurations HTTP
     * operation
     *
     * @return array
     */
    public function getConfigurations()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/configurations',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/ha/{haId}/acls HTTP
     * operation
     *
     * @return array
     */
    public function postAcls()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/ha/{haId}/acls',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'haId'      => $this->params->haIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/ha/{haId}/acls HTTP
     * operation
     *
     * @return array
     */
    public function getAcls()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/ha/{haId}/acls',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'haId'      => $this->params->haIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/configurations HTTP
     * operation
     *
     * @return array
     */
    public function postConfigurations()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/configurations',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /{version}/{accountId}/ha/{haId}/action HTTP
     * operation
     *
     * @return array
     */
    public function postAction()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/ha/{haId}/action',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'haId'      => $this->params->haIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /{version}/{accountId}/backups/{backupId} HTTP
     * operation
     *
     * @return array
     */
    public function deleteBackupId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/backups/{backupId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'backupId'  => $this->params->backupIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/flavors/{flavorId} HTTP
     * operation
     *
     * @return array
     */
    public function getFlavorId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/flavors/{flavorId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'flavorId'  => $this->params->flavorIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/backups/{backupId} HTTP
     * operation
     *
     * @return array
     */
    public function getBackupId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/backups/{backupId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'backupId'  => $this->params->backupIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/instances/{instanceId} HTTP
     * operation
     *
     * @return array
     */
    public function getInstanceId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PATCH /{version}/{accountId}/instances/{instanceId}
     * HTTP operation
     *
     * @return array
     */
    public function patchInstanceId()
    {
        return [
            'method' => 'PATCH',
            'path'   => '/{version}/{accountId}/instances/{instanceId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/schedules/{scheduleId} HTTP
     * operation
     *
     * @return array
     */
    public function getScheduleId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/schedules/{scheduleId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'scheduleId' => $this->params->scheduleIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /{version}/{accountId}/schedules/{scheduleId}
     * HTTP operation
     *
     * @return array
     */
    public function deleteScheduleId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/schedules/{scheduleId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'scheduleId' => $this->params->scheduleIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /{version}/{accountId}/instances/{instanceId} HTTP
     * operation
     *
     * @return array
     */
    public function putInstanceId()
    {
        return [
            'method' => 'PUT',
            'path'   => '/{version}/{accountId}/instances/{instanceId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /{version}/{accountId}/schedules/{scheduleId} HTTP
     * operation
     *
     * @return array
     */
    public function putScheduleId()
    {
        return [
            'method' => 'PUT',
            'path'   => '/{version}/{accountId}/schedules/{scheduleId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'scheduleId' => $this->params->scheduleIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /{version}/{accountId}/instances/{instanceId}
     * HTTP operation
     *
     * @return array
     */
    public function deleteInstanceId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/instances/{instanceId}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/datastores/{datastoreId}
     * HTTP operation
     *
     * @return array
     */
    public function getDatastoreId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/datastores/{datastoreId}',
            'params' => [
                'version'     => $this->params->versionUrl(),
                'accountId'   => $this->params->accountIdUrl(),
                'datastoreId' => $this->params->datastoreIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /{version}/{accountId}/ha/{haId}/acls/{address}
     * HTTP operation
     *
     * @return array
     */
    public function deleteAddress()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/ha/{haId}/acls/{address}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'haId'      => $this->params->haIdUrl(),
                'address'   => $this->params->addressUrl(),
            ],
        ];
    }

    /**
     * Returns information about PATCH /{version}/{accountId}/configurations/{configId}
     * HTTP operation
     *
     * @return array
     */
    public function patchConfigId()
    {
        return [
            'method' => 'PATCH',
            'path'   => '/{version}/{accountId}/configurations/{configId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'configId'  => $this->params->configIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/configurations/{configId}
     * HTTP operation
     *
     * @return array
     */
    public function getConfigId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/configurations/{configId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'configId'  => $this->params->configIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /{version}/{accountId}/configurations/{configId}
     * HTTP operation
     *
     * @return array
     */
    public function putConfigId()
    {
        return [
            'method' => 'PUT',
            'path'   => '/{version}/{accountId}/configurations/{configId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'configId'  => $this->params->configIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /{version}/{accountId}/configurations/{configId} HTTP operation
     *
     * @return array
     */
    public function deleteConfigId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/configurations/{configId}',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'configId'  => $this->params->configIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * /{version}/{accountId}/instances/{instanceId}/root HTTP operation
     *
     * @return array
     */
    public function postRoot()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/root',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /{version}/{accountId}/instances/{instanceId}/root
     * HTTP operation
     *
     * @return array
     */
    public function getRoot()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/root',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * /{version}/{accountId}/instances/{instanceId}/users HTTP operation
     *
     * @return array
     */
    public function putUsers()
    {
        return [
            'method' => 'PUT',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/instances/{instanceId}/users HTTP operation
     *
     * @return array
     */
    public function getUsers()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * /{version}/{accountId}/instances/{instanceId}/users HTTP operation
     *
     * @return array
     */
    public function postUsers()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/instances/{instanceId}/replicas HTTP operation
     *
     * @return array
     */
    public function getReplicas()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/replicas',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/instances/{instanceId}/databases HTTP operation
     *
     * @return array
     */
    public function getDatabases()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/databases',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * /{version}/{accountId}/instances/{instanceId}/databases HTTP operation
     *
     * @return array
     */
    public function postDatabases()
    {
        return [
            'method' => 'POST',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/databases',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/datastores/{datastoreId}/versions HTTP operation
     *
     * @return array
     */
    public function getVersions()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/datastores/{datastoreId}/versions',
            'params' => [
                'version'     => $this->params->versionUrl(),
                'accountId'   => $this->params->accountIdUrl(),
                'datastoreId' => $this->params->datastoreIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * /{version}/{accountId}/instances/{instanceId}/users/{name} HTTP operation
     *
     * @return array
     */
    public function putName()
    {
        return [
            'method' => 'PUT',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users/{name}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
                'name'       => $this->params->nameUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /{version}/{accountId}/instances/{instanceId}/users/{name} HTTP operation
     *
     * @return array
     */
    public function deleteName()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users/{name}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
                'name'       => $this->params->nameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/instances/{instanceId}/users/{name} HTTP operation
     *
     * @return array
     */
    public function getName()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users/{name}',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
                'name'       => $this->params->nameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/instances/{instanceId}/configuration HTTP operation
     *
     * @return array
     */
    public function getConfiguration()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/configuration',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/datastores/versions/{versionId}/parameters HTTP operation
     *
     * @return array
     */
    public function getParameters()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/datastores/versions/{versionId}/parameters',
            'params' => [
                'version'   => $this->params->versionUrl(),
                'accountId' => $this->params->accountIdUrl(),
                'versionId' => $this->params->versionIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * /{version}/{accountId}/instances/{instanceId}/users/{name}/databases HTTP
     * operation
     *
     * @return array
     */
    public function putDatabases()
    {
        return [
            'method' => 'PUT',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/users/{name}/databases',
            'params' => [
                'version'    => $this->params->versionUrl(),
                'accountId'  => $this->params->accountIdUrl(),
                'instanceId' => $this->params->instanceIdUrl(),
                'name'       => $this->params->nameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/datastores/{datastoreId}/versions/{versionId} HTTP
     * operation
     *
     * @return array
     */
    public function getVersionId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/datastores/{datastoreId}/versions/{versionId}',
            'params' => [
                'version'     => $this->params->versionUrl(),
                'accountId'   => $this->params->accountIdUrl(),
                'datastoreId' => $this->params->datastoreIdUrl(),
                'versionId'   => $this->params->versionIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * /{version}/{accountId}/instances/{instanceId}/databases/{databaseName} HTTP
     * operation
     *
     * @return array
     */
    public function deleteDatabaseName()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/{version}/{accountId}/instances/{instanceId}/databases/{databaseName}',
            'params' => [
                'version'      => $this->params->versionUrl(),
                'accountId'    => $this->params->accountIdUrl(),
                'instanceId'   => $this->params->instanceIdUrl(),
                'databaseName' => $this->params->databaseNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * /{version}/{accountId}/datastores/versions/{versionId}/parameters/{parameterId}
     * HTTP operation
     *
     * @return array
     */
    public function getParameterId()
    {
        return [
            'method' => 'GET',
            'path'   => '/{version}/{accountId}/datastores/versions/{versionId}/parameters/{parameterId}',
            'params' => [
                'version'     => $this->params->versionUrl(),
                'accountId'   => $this->params->accountIdUrl(),
                'versionId'   => $this->params->versionIdUrl(),
                'parameterId' => $this->params->parameterIdUrl(),
            ],
        ];
    }
}
