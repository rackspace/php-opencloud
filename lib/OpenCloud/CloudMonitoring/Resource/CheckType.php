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

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * CheckType class.
 */
class CheckType extends ReadOnlyResource
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string The name of the supported check type.
     */
    private $type;

    /**
     * @var array Check type fields.
     */
    private $fields;

    /**
     * Platforms on which an agent check type is supported. This is advisory information only - the check may still work
     * on other platforms, or report that check execution failed at runtime.
     *
     * @var array
     */
    private $supported_platforms;

    protected static $json_name = false;
    protected static $url_resource = 'check_types';
    protected static $json_collection_name = 'values';
}
