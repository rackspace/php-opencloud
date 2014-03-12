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

use OpenCloud\Common\Resource\PersistentResource;

/**
 * @codeCoverageIgnore
 */
class Resource extends PersistentResource
{
    public $links;
    /**
     * The name associated with the resource within the stack. This is the same
     * as the key in the `resources` object of your template.
     *
     * @var string
     */
    public $logical_resource_id;

    /**
     * The id of the resource within the OpenStack service that manages it
     *
     *  @var string
     */
    public $physical_resource_id;

    /**
     * The last status of this resource within the stack.
     *
     * @var string
     */
    public $resource_status;

    /**
     * The reason for this resource status.
     *
     * @var string
     */
    public $resource_status_reason;

    /**
     * The type of this resource
     *
     * @var string
     */
    public $resource_type;

    /**
     * Metadata associated with this resource. This is equivalent to the
     * `metadata` property of the resource within the template.
     *
     * @var array
     */
    public $resource_metadata;

    /**
     * When the resource was last updated.
     *
     * @var string
     */
    public $updated_time;

    protected static $url_resource = 'resources';
    protected static $json_name = 'resource';

    public function create($info = null)
    {
        $this->noCreate();
    }

    public function id()
    {
        return $this->physical_resource_id;
    }

    protected function primaryKeyField()
    {
        return 'physical_resource_id';
    }

    public function name()
    {
        return $this->logical_resource_id;
    }

    public function type()
    {
        return $this->resource_type;
    }

    public function status()
    {
        return $this->resource_status;
    }

    /**
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

    public function get() 
    {
        $service = $this->getParent()->getService();

        switch ($this->resource_type) {
            case 'AWS::EC2::Instance':
                $objSvc = 'Compute';
                $method = 'Server';
                $name = 'nova';
                break;
            default:
                throw new Exception(sprintf(
                    'Unknown resource type: %s',
                    $this->resource_type
                ));
        }

        return $service->connection()->$objSvc($name, $service->region())->$method($this->id());
    }
}
