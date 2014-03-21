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

use OpenCloud\Common\Resource\BaseResource;
use OpenCloud\Common\Resource\PersistentResource;

/**
 * A resource is a template artifact that represents some component of your
 * desired architecture (a Cloud Server, a group of scaled Cloud Servers, a
 * load balancer, some configuration management system, and so forth).
 *
 */
class Resource extends PersistentResource
{

    /**
     * Resource name.
     *
     * @var string
     */
    protected $resource_name;

    /**
     * The name associated with the resource within the stack. This is the same
     * as the key in the `resources` object of your template.
     *
     * @var string
     */
    protected $logical_resource_id;

    /**
     *  @var string
     */
    protected $physical_resource_id;

    /**
     * The last status of this resource within the stack.
     *
     * @var string
     */
    protected $resource_status;

    /**
     * The reason for this resource status.
     *
     * @var string
     */
    protected $resource_status_reason;

    /**
     * @var string
     */
    protected $resource_type;

    /**
     * Metadata associated with this resource. This is equivalent to the
     * `metadata` property of the resource within the template.
     *
     * @var array
     */
    protected $resource_metadata;

    /**
     * @var string
     */
    protected $updated_time;

    /**
     * @var array
     */
    protected $required_by;

    /**
     * @var array
     */
    protected $links;

    protected static $url_resource = 'resources';
    protected static $json_name = 'resource';

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resource_name;
    }

    /**
     * @return string
     */
    public function getLogicalResourceId()
    {
        return $this->logical_resource_id;
    }

    /**
     * The id of the resource within the OpenStack service that manages it
     *
     * @return string
     */
    public function getPhysicalResourcelId()
    {
        return $this->physical_resource_id;
    }

    /**
     * The status string for the resource.
     *
     * @return string
     * @see \OpenCloud\Orchestration\Enum\ResourceStatus
     */
    public function getResourceStatus()
    {
        return $this->resource_status;
    }

    /**
     * @return string
     */
    public function getResourceStatusReason()
    {
        return $this->resource_status_reason;
    }

    /**
     * The type of this resource
     *
     * @return string
     */
    public function getResourceType()
    {
        return $this->resource_type;
    }

    /**
     * When the resource was last updated.
     *
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    /**
     * @return array
     */
    public function getRequiredBy()
    {
        return $this->required_by;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function create($info = null)
    {
        $this->noCreate();
    }

    public function update($info = null)
    {
        $this->noUpdate();
    }

    public function delete()
    {
        $this->noDelete();
    }

}
