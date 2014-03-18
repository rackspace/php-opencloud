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
 * @codeCoverageIgnore
 */
class Resource extends PersistentResource
{
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

    protected static $url_resource = 'resources';
    protected static $json_name = 'resource';

    protected $links;

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return string
     */
    public function getLogicalId()
    {
        return $this->logical_resource_id;
    }

    /**
     * The id of the resource within the OpenStack service that manages it
     *
     * @return string
     */
    public function getPhysicalId()
    {
        return $this->physical_resource_id;
    }

    /**
     * The status string for the resource.
     *
     * @return string
     * @see \OpenCloud\Orchestration\Enum\ResourceStatus
     */
    public function getStatus()
    {
        return $this->resource_status;
    }

    /**
     * @return string
     */
    public function getStatusReason()
    {
        return $this->resource_status_reason;
    }

    /**
     * The type of this resource
     *
     * @return string
     */
    public function getType()
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

    public function create($info = null)
    {
        $this->noCreate();
    }

    /**
     * Get the object this Stack Resource refers to.
     *
     * @return BaseResource Varies depending on the type of resource being fetched
     */
    public function get()
    {
        return $this->getService()->getConcreteResource($this);
    }

    /**
     * {@inheritDoc}
     * @return string
     */
    public function id()
    {
        return $this->getPhysicalId();
    }

    protected function primaryKeyField()
    {
        return 'physical_resource_id';
    }

    /**
     * @deprecated
     * @return string
     */
    public function name()
    {
        return $this->getLogicalId();
    }

    /**
     * @deprecated
     * @return string
     */
    public function type()
    {
        return $this->getType();
    }

    /**
     * @deprecated
     * @return string
     */
    public function status()
    {
        return $this->resource_status;
    }

    /**
     * Return the resources metadata. Note that this metadata is specific to the orchestration service.
     *
     * @return \OpenCloud\Common\Metadata
     */
    public function getMetadata()
    {
        if (!isset($this->resource_metadata)) {
            /** @var \OpenCloud\Orchestration\Service $service */
            $service = $this->getService();
            $response = $this->getClient()->get($this->getUrl('metadata'), self::getJsonHeader())->send();
            $data = $response->json();
            $this->resource_metadata = $data['metadata'];
            $this->metadata->setArray($this->resource_metadata);
        }
        return $this->metadata;
    }

    /**
     * @deprecated
     * @return object decoded metadata
     */
    public function metadata()
    {
        if (!is_null($this->resource_metadata)) {
            return $this->resource_metadata;
        }
        $url = $this->url() . '/metadata';
        $response = $this->service()->request($url, 'GET', array('Accept' => 'application/json'));

        if ($json = $response->httpBody()) {
            return $this->resource_metadata = @json_decode($json)->metadata;
        } else {
            return array();
        }
    }
}
