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
 *
 */
class ResourceType extends ReadOnlyResource
{
    protected static $url_resource = 'resource_types';
    protected static $json_name = '';
    protected static $json_collection_name = 'resource_types';

    protected $resourceType;
    protected $attributes;
    protected $_properties; // Named so because the Base class has a $properties member.

    protected $aliases = array(
        'resource_type' => 'resourceType'
    );

    /**
     * Required to prevent the Base class from attempting to populate $this->properties.
     */
    protected function setProperties($properties)
    {
        $this->_properties = $properties;
    }

    public function getProperties()
    {
        return $this->_properties;
    }

    /**
     * Returns the template representation for this resource type.
     *
     * @return String template representation
     */
    public function getTemplate()
    {
        $url = clone $this->getUrl();
        $url->addPath('template');

        $response = $this->getClient()->get($url)->send();
        return $response->getBody();
    }

    protected function primaryKeyField()
    {
        return 'resourceType';
    }
}
