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

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Resource\PersistentResource;

/**
 * Supported template resource type.
 */
class ResourceType extends PersistentResource
{
    /**
     * Type of resource.
     *
     * @var string
     */
    protected $resource_type;

    /**
     * Attributes.
     *
     * @var object
     */
    protected $attributes;

    /**
     * Properties.
     *
     * @var object
     */
    protected $properties;

    protected static $json_name = "does_not_exist";
    protected static $json_collection_name = "resource_types";
    protected static $url_resource = "resource_types";

    protected function primaryKeyField()
    {
        return 'resource_type';
    }

    public function create($params = array())
    {
        return $this->noCreate();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }

    public function delete($params = array())
    {
        return $this->noDelete();
    }

    public function getResourceType()
    {
        return $this->resource_type;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * We have to define this method since there is a private
     * property with the same name (i.e. private $properties)
     * in OpenCloud\Common\Base that causes a conflict in the
     * implementation of the setProperty() method in the same
     * class. Sigh.
     */
    protected function setProperties($properties)
    {
        $this->properties = $properties;
    }
}
