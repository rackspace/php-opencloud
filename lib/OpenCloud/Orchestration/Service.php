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

use OpenCloud\Common\Metadata;
use OpenCloud\Common\Service\CatalogService;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * The Orchestration class represents the OpenStack Heat service.
 *
 * Heat is a service to orchestrate multiple composite cloud applications using
 * the AWS CloudFormation template format, through both an OpenStack-native ReST
 * API and a CloudFormation-compatible Query API.
 *
 */
class Service extends CatalogService
{
    const DEFAULT_NAME = 'cloudOrchestration';
    const DEFAULT_TYPE = 'orchestration';

    /**
     * Creates a new Stack object
     *
     * @api
     * @param  string $name the name of the stack
     * @return Resource\Stack
     */
    public function stack($name = null)
    {
        return new Resource\Stack($this, $name);
    }

    /**
     * Returns a Collection of Stack objects
     *
     * @api
     * @return \OpenCloud\Common\Collection
     */
    public function stackList()
    {
        return $this->collection('OpenCloud\Orchestration\Resource\Stack');
    }

    /**
     * Returns a Collection of ResourceType objects
     *
     * @api
     * @return Resource\ResourceType
     */
    public function resourceTypeList()
    {
        return $this->collection('OpenCloud\Orchestration\Resource\ResourceType');
    }

    /**
     * Returns a ResourceType object
     *
     * @api
     * @param  string $id the resource type
     * @return Resource\ResourceType
     */
    public function resourceType($id)
    {
        return new Resource\ResourceType($this, $id);
    }

    /**
     * Returns a BuildInfo object
     *
     * @api
     * @return Resource\BuildInfo
     */
    public function buildInfo()
    {
        $buildInfo = new Resource\BuildInfo($this);
        $buildInfo->refresh();
        return $buildInfo;
    }

    // TODO: Validate template
}
