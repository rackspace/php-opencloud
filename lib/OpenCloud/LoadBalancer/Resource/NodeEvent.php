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
 * This class will retrieve a list of events associated with the activity
 * between the node and the load balancer. The events report errors found with the node.
 */
class NodeEvent extends ReadOnlyResource
{
    public $detailedMessage;
    public $nodeId;
    public $id;
    public $type;
    public $description;
    public $category;
    public $severity;
    public $relativeUri;
    public $accountId;
    public $loadbalancerId;
    public $title;
    public $author;
    public $created;

    protected static $json_name = 'nodeServiceEvent';
    protected static $url_resource = 'nodes/events';
}
