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

namespace OpenCloud\LoadBalancer\Resource;

/**
 * Represents a resource that cannot be queried based on its ID. Instead, it
 * uses its parent URL, plus a generic path name, to determine its state.
 *
 * @package OpenCloud\LoadBalancer\Resource
 */
abstract class NonIdUriResource extends AbstractResource
{
    public function refresh($id = null, $url = null)
    {
        return $this->refreshFromParent();
    }
}
