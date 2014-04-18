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
 * An error page is the html file that is shown to the end user when an error
 * in the service has been thrown. By default every virtual server is provided
 * with the default error file. It is also possible to submit a custom error page
 * via the Load Balancers API. Refer to Section 4.2.3, “Error Page Operations”
 * for details (http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/List_Errorpage-d1e2218.html).
 */
class ErrorPage extends NonIdUriResource
{
    /**
     * HTML content for the custom error page. Must be 65536 characters or less.
     *
     * @var string
     */
    public $content;

    protected static $json_name = 'errorpage';
    protected static $url_resource = 'errorpage';

    protected $createKeys = array('content');

    public function create($params = array())
    {
        return $this->update($params);
    }
}
