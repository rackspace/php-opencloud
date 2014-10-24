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

use Guzzle\Http\Exception\ClientErrorResponseException;
use OpenCloud\Common\Exceptions\InvalidTemplateError;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Orchestration\Resource\ResourceType;
use OpenCloud\Orchestration\Resource\Stack;

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
     * @param string $name Name of stack to retrieve
     * @return Stack object
     */
    public function stack($name = null)
    {
        return $this->resource('Stack', $name);
    }

    /**
     * Previews a Stack from a template and returns it.
     *
     * @param array $params Stack preview parameters
     * @return Stack Object representing previewed stack
     */
    public function previewStack($params = array())
    {
        $stack = $this->stack();
        $stack->preview($params);
        return $stack;
    }

    /**
     * Creates a new Stack and returns it.
     *
     * @param array $params Stack creation parameters
     * @return Stack Object representing created stack
     */
    public function createStack($params = array())
    {
        $stack = $this->stack();
        $stack->create($params);
        return $stack;
    }

    /**
     * Adopts a Stack and returns it.
     *
     * @param array $params Stack adoption parameters
     * @return Stack Object representing adopted stack
     */
    public function adoptStack($params = array())
    {
        $stack = $this->stack();
        $stack->adopt($params);
        return $stack;
    }

    /**
     * Returns a Stack object associated with this Orchestration service
     *
     * @param string $name Name of stack to retrieve
     * @return Stack object
     */
    public function getStack($name)
    {
        return $this->stack($name);
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
     * @throws InvalidTemplateError if template is invalid
     */
    public function validateTemplate(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath('validate');

        // Aliases
        if (array_key_exists('templateUrl', $params)) {
            $params['template_url'] = $params['templateUrl'];
        }

        $json = json_encode($params);

        try {
            $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
        } catch (ClientErrorResponseException $e) {
            $response = Formatter::decode($e->getResponse());
            throw new InvalidTemplateError($response->explanation, $response->code);
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
