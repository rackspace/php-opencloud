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

namespace OpenCloud\Autoscale\Resource;

/**
 * This specifies the basic elements of the group. The Group Configuration contains:
 *
 * - Group Name
 * - Group Cooldown (how long a group has to wait before you can scale again in seconds)
 * - Minimum and Maximum number of entities
 *
 * @link https://github.com/rackerlabs/otter/blob/master/doc/getting_started.rst
 * @link http://docs.autoscale.apiary.io/
 */
class GroupConfiguration extends AbstractResource
{
    public $name;
    public $cooldown;
    public $minEntities;
    public $maxEntities;
    public $metadata;

    protected static $json_name = 'groupConfiguration';
    protected static $url_resource = 'config';

    public $createKeys = array(
        'name',
        'cooldown',
        'minEntities',
        'maxEntities'
    );

    public function create($params = array())
    {
        return $this->noCreate();
    }

    public function delete()
    {
        return $this->noDelete();
    }
}
