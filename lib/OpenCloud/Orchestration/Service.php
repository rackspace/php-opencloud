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
     * @api
     * @param string $id - the stack with the ID is retrieved
     * @returns Stack object
     */
    public function stack($id = null)
    {
        return new Stack($this, $id);
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
