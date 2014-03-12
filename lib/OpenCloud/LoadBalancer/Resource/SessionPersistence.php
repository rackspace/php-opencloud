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
 * Session persistence is a feature of the load balancing service that forces
 * multiple requests, of the same protocol, from clients to be directed to the
 * same node. This is common with many web applications that do not inherently
 * share application state between back-end servers. Two session persistence
 * modes are available, as described in the following table:
 *
 * * HTTP_COOKIE: A session persistence mechanism that inserts an HTTP cookie
 *      and is used to determine the destination back-end node. This is supported
 *      for HTTP load balancing only.
 *
 * * SOURCE_IP: A session persistence mechanism that will keep track of the
 *      source IP address that is mapped and is able to determine the destination
 *      back-end node. This is supported for HTTPS pass-through and non-HTTP
 *      load balancing only.
 */
class SessionPersistence extends NonIdUriResource
{
    /**
     * Mode in which session persistence mechanism operates. Can either be set
     * to HTTP_COOKIE or SOURCE_IP.
     *
     * @var string
     */
    public $persistenceType;

    protected static $json_name = 'sessionPersistence';
    protected static $url_resource = 'sessionpersistence';

    protected $createKeys = array('persistenceType');
}
