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

use OpenCloud\Common\PersistentObject;

/**
 * @codeCoverageIgnore
 */
class Resource extends PersistentObject
{
    protected $links;
    protected $logical_resource_id;
    protected $physical_resource_id;
    protected $resource_status;
    protected $resource_status_reason;
    protected $resource_type;
    protected $updated_time;

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
