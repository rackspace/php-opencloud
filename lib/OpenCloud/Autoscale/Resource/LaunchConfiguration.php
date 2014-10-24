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
 * This configuration specifies what to do when we want to create a new server.
 * What image to boot, on what flavor, and which load balancer to connect it to.
 *
 * The Launch Configuration Contains:
 *
 * - Launch Configuration Type (Only type currently supported is "launch_server")
 * - Arguments:
 *  - Server
 *   - name
 *   - flavor
 *   - imageRef (This is the ID of the Cloud Server image you will boot)
 *  - Load Balancer
 *   - loadBalancerId
 *   - port
 *
 * @link https://github.com/rackerlabs/otter/blob/master/doc/getting_started.rst
 * @link http://docs.autoscale.apiary.io/
 */
class LaunchConfiguration extends AbstractResource
{
    public $type;
    public $args;

    protected static $json_name = 'launchConfiguration';
    protected static $url_resource = 'launch';

    public $createKeys = array(
        'type',
        'args'
    );

    /**
     * {@inheritDoc}
     */
    public function create($params = array())
    {
        return $this->noCreate();
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        return $this->noDelete();
    }
}
