<?php declare(strict_types=1);

namespace Rackspace\Orchestration\v1;

use OpenCloud\Common\Api\AbstractApi;

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
    public function get(): array
    {
        return [
            'method' => 'GET',
            'path'   => '/',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST stacks HTTP operation
     *
     * @return array
     */
    public function postStacks(): array
    {
        return [
            'method' => 'POST',
            'path'   => 'stacks',
            'params' => [
                'default'             => $this->params->defaultJson(),
                'description'         => $this->params->descriptionJson(),
                'disableRollback'     => $this->params->disableRollbackJson(),
                'files'               => $this->params->filesJson(),
                'flavor'              => $this->params->flavorJson(),
                'getParam'            => $this->params->getParamJson(),
                'heatTemplateVersion' => $this->params->heatTemplateVersionJson(),
                'helloWorld'          => $this->params->helloWorldJson(),
                'image'               => $this->params->imageJson(),
                'keyName'             => $this->params->keyNameJson(),
                'parameters'          => $this->params->parametersJson(),
                'properties'          => $this->params->propertiesJson(),
                'resources'           => $this->params->resourcesJson(),
                'stackName'           => $this->params->stackNameJson(),
                'tags'                => $this->params->tagsJson(),
                'template'            => $this->params->templateJson(),
                'templateUrl'         => $this->params->templateUrlJson(),
                'timeoutMins'         => $this->params->timeoutMinsJson(),
                'type'                => $this->params->typeJson(),
                'userData'            => $this->params->userDataJson(),
            ],
        ];
    }

    /**
     * Returns information about GET stacks HTTP operation
     *
     * @return array
     */
    public function getStacks(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks',
            'params' => [
            ],
        ];
    }

    /**
     * Returns information about POST validate HTTP operation
     *
     * @return array
     */
    public function postValidate(): array
    {
        return [
            'method'  => 'POST',
            'path'    => 'validate',
            'jsonKey' => 'template_url',
            'params'  => [
                'environment' => $this->params->environmentJson(),
                'template'    => $this->params->templateJson(),
                'templateUrl' => $this->params->templateUrlJson(),
            ],
        ];
    }

    /**
     * Returns information about GET services HTTP operation
     *
     * @return array
     */
    public function getServices(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'services',
            'params' => [
            ],
        ];
    }

    /**
     * Returns information about GET build_info HTTP operation
     *
     * @return array
     */
    public function getBuildInfo(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'build_info',
            'params' => [
            ],
        ];
    }

    /**
     * Returns information about GET resource_types HTTP operation
     *
     * @return array
     */
    public function getResourceTypes(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'resource_types',
            'params' => [
            ],
        ];
    }

    /**
     * Returns information about POST stacks/preview HTTP operation
     *
     * @return array
     */
    public function postPreview(): array
    {
        return [
            'method' => 'POST',
            'path'   => 'stacks/preview',
            'params' => [
                'default'             => $this->params->defaultJson(),
                'description'         => $this->params->descriptionJson(),
                'disableRollback'     => $this->params->disableRollbackJson(),
                'files'               => $this->params->filesJson(),
                'flavor'              => $this->params->flavorJson(),
                'getParam'            => $this->params->getParamJson(),
                'heatTemplateVersion' => $this->params->heatTemplateVersionJson(),
                'helloWorld'          => $this->params->helloWorldJson(),
                'image'               => $this->params->imageJson(),
                'keyName'             => $this->params->keyNameJson(),
                'parameters'          => $this->params->parametersJson(),
                'properties'          => $this->params->propertiesJson(),
                'resources'           => $this->params->resourcesJson(),
                'stackName'           => $this->params->stackNameJson(),
                'template'            => $this->params->templateJson(),
                'templateUrl'         => $this->params->templateUrlJson(),
                'timeoutMins'         => $this->params->timeoutMinsJson(),
                'type'                => $this->params->typeJson(),
                'userData'            => $this->params->userDataJson(),
            ],
        ];
    }

    /**
     * Returns information about POST software_configs HTTP operation
     *
     * @return array
     */
    public function postSoftwareConfigs(): array
    {
        return [
            'method' => 'POST',
            'path'   => 'software_configs',
            'params' => [
                'config'      => $this->params->configJson(),
                'default'     => $this->params->defaultJson(),
                'description' => $this->params->descriptionJson(),
                'errorOutput' => $this->params->errorOutputJson(),
                'group'       => $this->params->groupJson(),
                'inputs'      => $this->params->inputsJson(),
                'name'        => $this->params->nameJson(),
                'options'     => $this->params->optionsJson(),
                'outputs'     => $this->params->outputsJson(),
                'type'        => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about GET template_versions HTTP operation
     *
     * @return array
     */
    public function getTemplateVersions(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'template_versions',
            'params' => [
            ],
        ];
    }

    /**
     * Returns information about GET stacks/{stack_name} HTTP operation
     *
     * @return array
     */
    public function getStackName(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST software_deployments HTTP
     * operation
     *
     * @return array
     */
    public function postSoftwareDeployments(): array
    {
        return [
            'method' => 'POST',
            'path'   => 'software_deployments',
            'params' => [
                'action'             => $this->params->actionJson(),
                'configId'           => $this->params->configIdJson(),
                'serverId'           => $this->params->serverIdJson(),
                'stackUserProjectId' => $this->params->stackUserProjectIdJson(),
                'status'             => $this->params->statusJson(),
                'statusReason'       => $this->params->statusReasonJson(),
            ],
        ];
    }

    /**
     * Returns information about GET software_deployments HTTP
     * operation
     *
     * @return array
     */
    public function getSoftwareDeployments(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'software_deployments',
            'params' => [
            ],
        ];
    }

    /**
     * Returns information about GET resource_types/{type_name} HTTP
     * operation
     *
     * @return array
     */
    public function getTypeName(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'resource_types/{type_name}',
            'params' => [
                'typeName' => $this->params->typeNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET stacks/{stack_name}/events HTTP
     * operation
     *
     * @return array
     */
    public function getEvents(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/events',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET software_configs/{config_id} HTTP
     * operation
     *
     * @return array
     */
    public function getConfigId(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'software_configs/{config_id}',
            'params' => [
                'configId' => $this->params->configIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE software_configs/{config_id}
     * HTTP operation
     *
     * @return array
     */
    public function deleteConfigId(): array
    {
        return [
            'method' => 'DELETE',
            'path'   => 'software_configs/{config_id}',
            'params' => [
                'configId' => $this->params->configIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET stacks/{stack_name}/resources HTTP
     * operation
     *
     * @return array
     */
    public function getResources(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/resources',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT stacks/{stack_name}/{stack_id}
     * HTTP operation
     *
     * @return array
     */
    public function putStackId(): array
    {
        return [
            'method' => 'PUT',
            'path'   => 'stacks/{stack_name}/{stack_id}',
            'params' => [
                'stackName'           => $this->params->stackNameUrl(),
                'stackId'             => $this->params->stackIdUrl(),
                'default'             => $this->params->defaultJson(),
                'description'         => $this->params->descriptionJson(),
                'environment'         => $this->params->environmentJson(),
                'files'               => $this->params->filesJson(),
                'flavor'              => $this->params->flavorJson(),
                'getParam'            => $this->params->getParamJson(),
                'heatTemplateVersion' => $this->params->heatTemplateVersionJson(),
                'helloWorld'          => $this->params->helloWorldJson(),
                'image'               => $this->params->imageJson(),
                'keyName'             => $this->params->keyNameJson(),
                'parameters'          => $this->params->parametersJson(),
                'properties'          => $this->params->propertiesJson(),
                'resources'           => $this->params->resourcesJson(),
                'tags'                => $this->params->tagsJson(),
                'template'            => $this->params->templateJson(),
                'timeoutMins'         => $this->params->timeoutMinsJson(),
                'type'                => $this->params->typeJson(),
                'userData'            => $this->params->userDataJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE stacks/{stack_name}/{stack_id}
     * HTTP operation
     *
     * @return array
     */
    public function deleteStackId(): array
    {
        return [
            'method' => 'DELETE',
            'path'   => 'stacks/{stack_name}/{stack_id}',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
                'stackId'   => $this->params->stackIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET stacks/{stack_name}/{stack_id}
     * HTTP operation
     *
     * @return array
     */
    public function getStackId(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/{stack_id}',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
                'stackId'   => $this->params->stackIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * resource_types/{type_name}/template HTTP operation
     *
     * @return array
     */
    public function getTemplate(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'resource_types/{type_name}/template',
            'params' => [
                'typeName' => $this->params->typeNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * software_deployments/{deployment_id} HTTP operation
     *
     * @return array
     */
    public function getDeploymentId(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'software_deployments/{deployment_id}',
            'params' => [
                'deploymentId' => $this->params->deploymentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * software_deployments/{deployment_id} HTTP operation
     *
     * @return array
     */
    public function deleteDeploymentId(): array
    {
        return [
            'method' => 'DELETE',
            'path'   => 'software_deployments/{deployment_id}',
            'params' => [
                'deploymentId' => $this->params->deploymentIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT
     * software_deployments/{deployment_id} HTTP operation
     *
     * @return array
     */
    public function putDeploymentId(): array
    {
        return [
            'method' => 'PUT',
            'path'   => 'software_deployments/{deployment_id}',
            'params' => [
                'deploymentId'     => $this->params->deploymentIdUrl(),
                'action'           => $this->params->actionJson(),
                'deployStatusCode' => $this->params->deployStatusCodeJson(),
                'deployStderr'     => $this->params->deployStderrJson(),
                'deployStdout'     => $this->params->deployStdoutJson(),
                'outputValues'     => $this->params->outputValuesJson(),
                'result'           => $this->params->resultJson(),
                'status'           => $this->params->statusJson(),
                'statusReason'     => $this->params->statusReasonJson(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * stacks/{stack_name}/{stack_id}/actions HTTP operation
     *
     * @return array
     */
    public function postActions(): array
    {
        return [
            'method'  => 'POST',
            'path'    => 'stacks/{stack_name}/{stack_id}/actions',
            'jsonKey' => 'resume',
            'params'  => [
                'stackName' => $this->params->stackNameUrl(),
                'stackId'   => $this->params->stackIdUrl(),
                'resume'    => $this->params->resumeJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * stacks/{stack_name}/{stack_id}/abandon HTTP operation
     *
     * @return array
     */
    public function deleteAbandon(): array
    {
        return [
            'method' => 'DELETE',
            'path'   => 'stacks/{stack_name}/{stack_id}/abandon',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
                'stackId'   => $this->params->stackIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * stacks/{stack_name}/{stack_id}/snapshots HTTP operation
     *
     * @return array
     */
    public function postSnapshots(): array
    {
        return [
            'method'  => 'POST',
            'path'    => 'stacks/{stack_name}/{stack_id}/snapshots',
            'jsonKey' => 'name',
            'params'  => [
                'stackName' => $this->params->stackNameUrl(),
                'stackId'   => $this->params->stackIdUrl(),
                'name'      => $this->params->nameJson(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * stacks/{stack_name}/{stack_id}/snapshots HTTP operation
     *
     * @return array
     */
    public function getSnapshots(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/{stack_id}/snapshots',
            'params' => [
                'stackName' => $this->params->stackNameUrl(),
                'stackId'   => $this->params->stackIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * software_deployments/metadata/{server_id} HTTP operation
     *
     * @return array
     */
    public function getServerId(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'software_deployments/metadata/{server_id}',
            'params' => [
                'serverId' => $this->params->serverIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * stacks/{stack_name}/{stack_id}/snapshots/{snapshot_id} HTTP
     * operation
     *
     * @return array
     */
    public function getSnapshotId(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/{stack_id}/snapshots/{snapshot_id}',
            'params' => [
                'stackName'  => $this->params->stackNameUrl(),
                'stackId'    => $this->params->stackIdUrl(),
                'snapshotId' => $this->params->snapshotIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE
     * stacks/{stack_name}/{stack_id}/snapshots/{snapshot_id} HTTP
     * operation
     *
     * @return array
     */
    public function deleteSnapshotId(): array
    {
        return [
            'method' => 'DELETE',
            'path'   => 'stacks/{stack_name}/{stack_id}/snapshots/{snapshot_id}',
            'params' => [
                'stackName'  => $this->params->stackNameUrl(),
                'stackId'    => $this->params->stackIdUrl(),
                'snapshotId' => $this->params->snapshotIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * stacks/{stack_name}/{stack_id}/resources/{resource_name} HTTP
     * operation
     *
     * @return array
     */
    public function getResourceName(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/{stack_id}/resources/{resource_name}',
            'params' => [
                'stackName'    => $this->params->stackNameUrl(),
                'stackId'      => $this->params->stackIdUrl(),
                'resourceName' => $this->params->resourceNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * stacks/{stack_name}/{stack_id}/snapshots/{snapshot_id}/restore
     * HTTP operation
     *
     * @return array
     */
    public function postRestore(): array
    {
        return [
            'method' => 'POST',
            'path'   => 'stacks/{stack_name}/{stack_id}/snapshots/{snapshot_id}/restore',
            'params' => [
                'stackName'  => $this->params->stackNameUrl(),
                'stackId'    => $this->params->stackIdUrl(),
                'snapshotId' => $this->params->snapshotIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST
     * stacks/{stack_name}/{stack_id}/resources/{resource_name}/signal
     * HTTP operation
     *
     * @return array
     */
    public function postSignal(): array
    {
        return [
            'method' => 'POST',
            'path'   => 'stacks/{stack_name}/{stack_id}/resources/{resource_name}/signal',
            'params' => [
                'stackName'    => $this->params->stackNameUrl(),
                'stackId'      => $this->params->stackIdUrl(),
                'resourceName' => $this->params->resourceNameUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET
     * stacks/{stack_name}/{stack_id}/resources/{resource_name}/events/{event_id}
     * HTTP operation
     *
     * @return array
     */
    public function getEventId(): array
    {
        return [
            'method' => 'GET',
            'path'   => 'stacks/{stack_name}/{stack_id}/resources/{resource_name}/events/{event_id}',
            'params' => [
                'stackName'    => $this->params->stackNameUrl(),
                'stackId'      => $this->params->stackIdUrl(),
                'resourceName' => $this->params->resourceNameUrl(),
                'eventId'      => $this->params->eventIdUrl(),
            ],
        ];
    }
}