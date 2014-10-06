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

namespace OpenCloud\Orchestration;

use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Orchestration\Resource\Stack;
use OpenCloud\Orchestration\Resource\ResourceType;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * The Orchestration class represents the OpenStack Heat service.
 *
 * Heat is a service to orchestrate multiple composite cloud applications using
 * the AWS CloudFormation template format, through both an OpenStack-native ReST
 * API and a CloudFormation-compatible Query API.
 *
 * @codeCoverageIgnore
 */
class Service extends CatalogService
{
    const DEFAULT_TYPE = 'orchestration';
    const DEFAULT_NAME = 'cloudOrchestration';

    /**
     * Returns a Stack object associated with this Orchestration service
     *
     * @param string $id - the stack with the ID is retrieved
     * @return Stack object
     */
    public function stack($id = null)
    {
        return $this->resource('Stack', $id);
    }

    /**
     * Returns a Stack object associated with this Orchestration service
     *
     * @param string $id - the stack with the ID is retrieved
     * @return Stack object
     */
    public function getStack($id)
    {
        return $this->stack($id);
    }

    /**
     * Returns a list of stacks you created
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listStacks(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Stack::resourceName())->setQuery($params);

        return $this->resourceList('Stack', $url);
    }

    /**
     * Returns a list of resource types available
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listResourceTypes(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(ResourceType::resourceName())->setQuery($params);

        return $this->resourceList('ResourceType', $url);
    }

    /**
     * Returns a ResourceType object associated with this Orchestration service
     *
     * @param string $id - the resource type with the ID is retrieved
     * @return ResourceType object
     */
    public function getResourceType($id)
    {
        return $this->resource('ResourceType', $id);
    }

    /**
     * Returns a BuildInfo object associated with this Orchestration service
     *
     * @return BuildInfo object
     */
    public function getBuildInfo()
    {
        $buildInfo = $this->resource('BuildInfo');
        $buildInfo->refresh();
        return $buildInfo;        
    }

    /**
     * Validates the given template
     *
     * @return Boolean True, if template is valid; False, otherwise
     */
    public function validateTemplate(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath('validate');

        $json = json_encode($params);

        try {
            $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
            return true;
        } catch (ClientErrorResponseException $e) {
            return false;
        }
    }

    /**
     * Return namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return array();
    }
}
