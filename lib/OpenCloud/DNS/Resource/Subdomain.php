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

namespace OpenCloud\DNS\Resource;

/**
 * The Subdomain is basically another domain, albeit one that is a child of
 * a parent domain. In terms of the code involved, the JSON is slightly
 * different than a top-level domain, and the parent is a domain instead of
 * the DNS service itself.
 */
class Subdomain extends Domain
{
    protected static $json_name = false;
    protected static $json_collection_name = 'domains';
    protected static $url_resource = 'subdomains';
}
