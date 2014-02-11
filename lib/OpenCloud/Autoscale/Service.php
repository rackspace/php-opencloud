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

namespace OpenCloud\Autoscale;

use OpenCloud\Common\Service\CatalogService;

/**
 * The Autoscale class represents the OpenStack Otter service.
 */
class Service extends CatalogService
{
    const DEFAULT_TYPE = 'rax:autoscale';
    const DEFAULT_NAME = 'autoscale';

    /**
     * Autoscale resources.
     *
     * @var    array
     * @access private
     */
    public $resources = array(
        'Group',
        'GroupConfiguration',
        'LaunchConfiguration',
        'ScalingPolicy'
    );

    /**
     * Convenience method for getting an autoscale group.
     *
     * @param  mixed $info
     * @return AbstractResource
     */
    public function group($info = null)
    {
        return $this->resource('Group', $info);
    }

    /**
     * Convenience method for getting a list of autoscale groups.
     *
     * @return OpenCloud\Common\Collection
     */
    public function groupList()
    {
        return $this->resourceList('Group');
    }
}
