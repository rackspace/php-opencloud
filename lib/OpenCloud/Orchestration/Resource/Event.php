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

use OpenCloud\Common\Resource\ReadOnlyResource;

/**
 * Class that represents an event associated with a resource in a stack.
 * @see http://developer.openstack.org/api-ref-orchestration-v1.html#stack-events
 *
 * @package OpenCloud\Orchestration\Resource
 */
class Event extends ReadOnlyResource
{
    protected static $url_resource = 'events';
    protected static $json_name = 'event';

    protected $id;
    protected $time;
    protected $resourceName;
    protected $resourceLogicalId;
    protected $resourcePhysicalId;
    protected $resourceStatus;
    protected $resourceStatusReason;
    protected $resourceProperties;
    protected $resourceType;
    protected $links;

    protected $aliases = array(
        'event_time'             => 'time',
        'resource_name'          => 'resourceName',
        'logical_resource_id'    => 'resourceLogicalId',
        'physical_resource_id'   => 'resourcePhysicalId',
        'resource_status'        => 'resourceStatus',
        'resource_status_reason' => 'resourceStatusReason',
        'resource_type'          => 'resourceType',
        'resource_properties'    => 'resourceProperties'
    );
}
