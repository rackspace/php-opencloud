<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Orchestration\Resource;

use OpenCloud\Common\Resource\PersistentResource;

/**
 *
 */
class Stack extends PersistentResource
{
    protected static $url_resource = 'stacks';
    protected static $json_name = 'stack';

    protected $id;
    protected $disableRollback;
    protected $description;
    protected $parameters;
    protected $environment;
    protected $files;
    protected $name;
    protected $status;
    protected $statusReason;
    protected $outputs;
    protected $creationTime;
    protected $updatedTime;
    protected $timeoutMins;
    protected $templateUrl;
    protected $template;
    protected $adoptStackData;

    protected $aliases = array(
        'disable_rollback'    => 'disableRollback',
        'stack_name'          => 'name',
        'stack_status'        => 'status',
        'stack_status_reason' => 'statusReason',
        'creation_time'       => 'creationTime',
        'updated_time'        => 'updatedTime',
        'timeout_mins'        => 'timeoutMins',
        'template_url'        => 'templateUrl',
        'adopt_stack_data'    => 'adoptStackData'
    );

    protected $createKeys = array(
        'name',
        'templateUrl',
        'template',
        'environment',
        'files',
        'parameters',
        'timeoutMins',
        'adoptStackData'
    );

    protected $updateKeys = array(
        'templateUrl',
        'template',
        'environment',
        'files',
        'parameters',
        'timeoutMins'
    );

    protected function createJson()
    {
        $createJson = parent::createJson();
        return $createJson->{self::$json_name};
    }

    protected function updateJson($params = array())
    {
        $updateJson = parent::updateJson($params);
        return $updateJson->{self::$json_name};
    }

    /**
     * Creates a new stack by adopting resources from an abandoned stack
     *
     * @param array $params Adopt stack parameters
     * @return Guzzle\Http\Message\Response
     */
    public function adopt($params)
    {
        // Validate that required parameters are provided
        $requiredParameterName = 'adoptStackData';
        if (!array_key_exists($requiredParameterName, $params)) {
            throw new \InvalidArgumentException($requiredParameterName . ' is a required option');
        }

        return $this->create($params);
    }

    /**
     * Previews the stack without actually creating it
     *
     * @param array $params Preview stack parameters
     * @return Guzzle\Http\Message\Response
     */
    public function preview($params = array())
    {
        // set parameters
        if (!empty($params)) {
            $this->populate($params, false);
        }

        // construct the JSON
        $json = json_encode($this->createJson());
        $this->checkJsonError();

        $previewUrl = $this->previewUrl();
        $response = $this->getClient()->post($previewUrl, self::getJsonHeader(), $json)->send();
        
        $decoded = $this->parseResponse($response);
        $this->populate($decoded);

        return $response;
    }

    /**
     * Abandons the stack and returns abandoned stack data.
     *
     * @return string Abandoned stack data (which could be passed to the adopt stack operation as adoptStackData).
     */
    public function abandon()
    {
        $abandonUrl = $this->abandonUrl();
        $response = $this->getClient()->delete($abandonUrl)->send();
        return $response->getBody(true);
    }

    /**
     * Returns a Resource object associated with this Stack
     *
     * @param string $name Stack resource name
     * @return Resource object
     */
    public function getResource($name)
    {
        return $this->getService()->resource('Resource', $name, $this);
    }

    /**
     * Returns a list of Resources associated with this Stack
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listResources(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Resource::resourceName())->setQuery($params);

        return $this->getService()->resourceList('Resource', $url, $this);
    }

    protected function previewUrl()
    {
        return $this->getParent()->url('preview');
    }

    protected function abandonUrl()
    {
        return $this->getParent()->url('abandon');
    }

    protected function primaryKeyField()
    {
        return 'name';
    }

    public function getUrl($path = NULL, array $query = array())
    {
        $url = parent::getUrl($path, $query);
        if ($this->id) {
            $url->addPath($this->id);
        }
        return $url;
    }

}
