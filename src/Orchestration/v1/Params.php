<?php

namespace Rackspace\Orchestration\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about tenantId parameter
     *
     * @return array
     */
    public function tenantIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'tenant_id',
        ];
    }

    /**
     * Returns information about default parameter
     *
     * @return array
     */
    public function defaultJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about description parameter
     *
     * @return array
     */
    public function descriptionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about disableRollback parameter
     *
     * @return array
     */
    public function disableRollbackJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'disable_rollback',
        ];
    }

    /**
     * Returns information about files parameter
     *
     * @return array
     */
    public function filesJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'properties'  => [],
            'required'    => false,
            'description' => 'Supplies the contents of files referenced in the template or the environment. Stack templates and resource templates can explicitly reference files by using the 9b16cfdf93b629f005cbc4ce1d6865a45c7f04d0 intrinsic function. In addition, the 6b084893174737edfb8bca611c74c2454bf43cf7 parameter can contain implicit references to files. The value is a JSON object, where each key is a relative or absolute URI which serves as the name of a file, and the associated value provides the contents of the file. The following code shows the general structure of this parameter. f4a3794b184c464d2f67cfefa6932bd89c70c9c7 Additionally, some template authors encode their user data in a local file. The Orchestration client examines the template for the 7f7f2219e4a8a5fcfe8f2b998c10a3d107cba523 intrinsic function and adds an entry to the 2a8c010fed85045a76a8bac662cf918e3e500059 map with the path to the file as the name and the file contents as the value. So, a simple example looks like this: 81c4f8821839b409403ca4847627a74113fed327 Do not use this parameter to provide the content of the template located at the address specified by 9a7374a05d34a955fcf545e1b318764ba07dcad8. Instead, use the 1b11bc4f3585482695ad9c42840fba13101956ed parameter to supply the template content as part of the request.',
        ];
    }

    /**
     * Returns information about flavor parameter
     *
     * @return array
     */
    public function flavorJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'getParam' => $this->getParamJson(),
            ],
        ];
    }

    /**
     * Returns information about getParam parameter
     *
     * @return array
     */
    public function getParamJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'get_param',
        ];
    }

    /**
     * Returns information about heatTemplateVersion parameter
     *
     * @return array
     */
    public function heatTemplateVersionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'heat_template_version',
        ];
    }

    /**
     * Returns information about helloWorld parameter
     *
     * @return array
     */
    public function helloWorldJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'hello_world',
            'properties' => [
                'type'       => $this->typeJson(),
                'properties' => $this->propertiesJson(),
            ],
        ];
    }

    /**
     * Returns information about image parameter
     *
     * @return array
     */
    public function imageJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about keyName parameter
     *
     * @return array
     */
    public function keyNameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'key_name',
        ];
    }

    /**
     * Returns information about parameters parameter
     *
     * @return array
     */
    public function parametersJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'properties'  => [
                'flavor' => $this->flavorJson(),
            ],
            'required'    => false,
            'description' => 'Supplies arguments for parameters defined in the stack template. The value is a JSON object, where each key is the name of a parameter defined in the template and the associated value is the argument to use for that parameter when instantiating the template. The following code shows the general structure of this parameter. In the example, 1c832be96b18a613825802962b91cfdf8ec7a7f2 and 69875701fee345ab2818588c5be4027420d4bb90 would be the names of two parameters defined in the template. 14b0dc08d96f70e9797c67f4b42750502d75f055 While the service accepts JSON numbers for parameters with the type dedebe471e013840e310578dcd5ad8644beaad6e and JSON objects for parameters with the type 2296d6c2b32c64509af28b9acdfb80247047ad0c, all parameter values are converted to their string representation for storage in the created Stack. Clients are encouraged to send all parameter values using their string representation for consistency between requests and responses from the Orchestration service. A value must be provided for each template parameter which does not specify a default value. However, this parameter is not allowed to contain JSON properties with names that do not match a parameter defined in the template. The 8746a4fb9b2e6a4db8e8aedb0067b89f490db5ad parameter maps logical file names to file contents. Both the 30a03a02081f7bd4b87eb28af138aae19dd2e0d8 intrinsic function and provider template functionality use this mapping. When you want to use a provider template, for example, the Orchestration service adds an entry to the baf734c27311a7d8a29ef2a0baa76d90fe87c93e map by using: * The URL of the provider template as the name. * The contents of that file as the value. Additionally, some template authors encode their user data in a local file. The Orchestration client examines the template for the d117633609520aeb31a051e41f527e0e510f4aca intrinsic function and adds an entry to the 129f25bada017d4f7d87aee8b660b853b2dcd188 map with the path to the file as the name and the file contents as the value. So, a simple example looks like this: 82190b62435fa48617ac63077e71e74b0444e4eb',
        ];
    }

    /**
     * Returns information about properties parameter
     *
     * @return array
     */
    public function propertiesJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'keyName'  => $this->keyNameJson(),
                'flavor'   => $this->flavorJson(),
                'image'    => $this->imageJson(),
                'userData' => $this->userDataJson(),
            ],
        ];
    }

    /**
     * Returns information about resources parameter
     *
     * @return array
     */
    public function resourcesJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'properties' => [
                'helloWorld' => $this->helloWorldJson(),
            ],
        ];
    }

    /**
     * Returns information about stackName parameter
     *
     * @return array
     */
    public function stackNameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'stack_name',
        ];
    }

    /**
     * Returns information about tags parameter
     *
     * @return array
     */
    public function tagsJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'One or more simple string tags to associate with the stack. To associate multiple tags with a stack, separate the tags with commas. For example, e4af1c74441adbfa136b1750528208016d81c784.',
        ];
    }

    /**
     * Returns information about template parameter
     *
     * @return array
     */
    public function templateJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'properties'  => [
                'heatTemplateVersion' => $this->heatTemplateVersionJson(),
                'description'         => $this->descriptionJson(),
                'parameters'          => $this->parametersJson(),
                'resources'           => $this->resourcesJson(),
            ],
            'required'    => false,
            'description' => 'The stack template on which to perform the specified operation. This parameter is always provided as a 88c63de52d4d9bda1abb713e81a8ad7989b07874 in the JSON request body. The content of the string is a JSON- or YAML-formatted Orchestration template. For example: 353047fb9d3e841608b7db677c3d231d4f924251 This parameter is required only when you omit the 59bec1f3360fe62cab9f8c505c3a7c7933cd8eb4 parameter. If you specify both parameters, this value overrides the 0096f3e0dfaf7ac88d4c56fae53c7c01fd5454ae parameter value.',
        ];
    }

    /**
     * Returns information about templateUrl parameter
     *
     * @return array
     */
    public function templateUrlJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'A URI to the location containing the stack template on which to perform the specified operation. See the description of the 4dddaceadc844787452b0d5c92de405fee0a1aa1 parameter for information about the expected template content located at the URI. This parameter is only required when you omit the 489f1196c8aa357c6e2c48eddda25eba83412b17 parameter. If you specify both parameters, this parameter is ignored.',
            'sentAs'      => 'template_url',
        ];
    }

    /**
     * Returns information about timeoutMins parameter
     *
     * @return array
     */
    public function timeoutMinsJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'timeout_mins',
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
     * Returns information about userData parameter
     *
     * @return array
     */
    public function userDataJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'user_data',
        ];
    }

    /**
     * Returns information about action parameter
     *
     * @return array
     */
    public function actionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about adoptStackData parameter
     *
     * @return array
     */
    public function adoptStackDataJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'adopt_stack_data',
            'properties'  => [
                'action'    => $this->actionJson(),
                'id'        => $this->idJson(),
                'name'      => $this->nameJson(),
                'resources' => $this->resourcesJson(),
                'status'    => $this->statusJson(),
                'template'  => $this->templateJson(),
            ],
            'required'    => false,
            'description' => 'Existing resources data to adopt a stack. Data returned by abandon stack could be provided as c8bc91598f645f7fb64e33042b3762e4256bf554.',
        ];
    }

    /**
     * Returns information about environment parameter
     *
     * @return array
     */
    public function environmentJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'A JSON environment for the stack. ',
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
            'properties' => [],
        ];
    }

    /**
     * Returns information about myServer parameter
     *
     * @return array
     */
    public function myServerJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'MyServer',
            'properties' => [
                'action'       => $this->actionJson(),
                'metadata'     => $this->metadataJson(),
                'name'         => $this->nameJson(),
                'resourceData' => $this->resourceDataJson(),
                'resourceId'   => $this->resourceIdJson(),
                'status'       => $this->statusJson(),
                'type'         => $this->typeJson(),
            ],
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
     * Returns information about resourceData parameter
     *
     * @return array
     */
    public function resourceDataJson()
    {
        return [
            'type'       => self::OBJECT_TYPE,
            'location'   => self::JSON,
            'sentAs'     => 'resource_data',
            'properties' => [],
        ];
    }

    /**
     * Returns information about resourceId parameter
     *
     * @return array
     */
    public function resourceIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'resource_id',
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
     * Returns information about config parameter
     *
     * @return array
     */
    public function configJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about errorOutput parameter
     *
     * @return array
     */
    public function errorOutputJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'error_output',
        ];
    }

    /**
     * Returns information about group parameter
     *
     * @return array
     */
    public function groupJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Namespace that groups this software configuration by when it is delivered to a server. This setting might simply define which configuration tool performs the configuration.',
        ];
    }

    /**
     * Returns information about inputs parameter
     *
     * @return array
     */
    public function inputsJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'itemSchema'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'default'     => $this->defaultJson(),
                    'type'        => $this->typeJson(),
                    'name'        => $this->nameJson(),
                    'description' => $this->descriptionJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Schema that represents the inputs that this software configuration expects.',
        ];
    }

    /**
     * Returns information about options parameter
     *
     * @return array
     */
    public function optionsJson()
    {
        return [
            'type'        => 'NULL',
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Map that contains options that are specific to the configuration management tool that this resource uses.',
        ];
    }

    /**
     * Returns information about outputs parameter
     *
     * @return array
     */
    public function outputsJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'itemSchema'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'type'        => $this->typeJson(),
                    'name'        => $this->nameJson(),
                    'errorOutput' => $this->errorOutputJson(),
                    'description' => $this->descriptionJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Schema that represents the outputs that this software configuration produces.',
        ];
    }

    /**
     * Returns information about stackName parameter
     *
     * @return array
     */
    public function stackNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'stack_name',
        ];
    }

    /**
     * Returns information about configId parameter
     *
     * @return array
     */
    public function configIdJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'config_id',
        ];
    }

    /**
     * Returns information about serverId parameter
     *
     * @return array
     */
    public function serverIdJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'server_id',
            'required'    => false,
            'description' => 'The ID of the compute server to which the configuration applies.',
        ];
    }

    /**
     * Returns information about stackUserProjectId parameter
     *
     * @return array
     */
    public function stackUserProjectIdJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'stack_user_project_id',
            'required'    => false,
            'description' => 'Authentication project ID, which can also perform operations on this deployment.',
        ];
    }

    /**
     * Returns information about statusReason parameter
     *
     * @return array
     */
    public function statusReasonJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'status_reason',
            'required'    => false,
            'description' => 'Error description for the last status change, which is 5b094ce148deecf3461a9d3d9a326012573bf7b3 status.',
        ];
    }

    /**
     * Returns information about typeName parameter
     *
     * @return array
     */
    public function typeNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'type_name',
        ];
    }

    /**
     * Returns information about configId parameter
     *
     * @return array
     */
    public function configIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'config_id',
        ];
    }

    /**
     * Returns information about stackId parameter
     *
     * @return array
     */
    public function stackIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'stack_id',
        ];
    }

    /**
     * Returns information about deploymentId parameter
     *
     * @return array
     */
    public function deploymentIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'deployment_id',
        ];
    }

    /**
     * Returns information about deployStatusCode parameter
     *
     * @return array
     */
    public function deployStatusCodeJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'deploy_status_code',
        ];
    }

    /**
     * Returns information about deployStderr parameter
     *
     * @return array
     */
    public function deployStderrJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'deploy_stderr',
        ];
    }

    /**
     * Returns information about deployStdout parameter
     *
     * @return array
     */
    public function deployStdoutJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'sentAs'   => 'deploy_stdout',
        ];
    }

    /**
     * Returns information about outputValues parameter
     *
     * @return array
     */
    public function outputValuesJson()
    {
        return [
            'type'        => self::OBJECT_TYPE,
            'location'    => self::JSON,
            'sentAs'      => 'output_values',
            'properties'  => [
                'deployStdout'     => $this->deployStdoutJson(),
                'deployStderr'     => $this->deployStderrJson(),
                'deployStatusCode' => $this->deployStatusCodeJson(),
                'result'           => $this->resultJson(),
            ],
            'required'    => false,
            'description' => 'Map of output values for the deployment, as signalled from the server.',
        ];
    }

    /**
     * Returns information about result parameter
     *
     * @return array
     */
    public function resultJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about resume parameter
     *
     * @return array
     */
    public function resumeJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about check parameter
     *
     * @return array
     */
    public function checkJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about suspend parameter
     *
     * @return array
     */
    public function suspendJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about cancelUpdate parameter
     *
     * @return array
     */
    public function cancelUpdateJson()
    {
        return [
            'type'     => 'NULL',
            'location' => self::JSON,
            'sentAs'   => 'cancel_update',
        ];
    }

    /**
     * Returns information about serverId parameter
     *
     * @return array
     */
    public function serverIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'server_id',
        ];
    }

    /**
     * Returns information about snapshotId parameter
     *
     * @return array
     */
    public function snapshotIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'snapshot_id',
        ];
    }

    /**
     * Returns information about resourceName parameter
     *
     * @return array
     */
    public function resourceNameUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'resource_name',
        ];
    }

    /**
     * Returns information about eventId parameter
     *
     * @return array
     */
    public function eventIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
            'sentAs'   => 'event_id',
        ];
    }


}
