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
 * All load balancers utilize an algorithm that defines how traffic should be
 * directed between back-end nodes. The default algorithm for newly created load
 * balancers is RANDOM, which can be overridden at creation time or changed
 * after the load balancer has been initially provisioned. The algorithm name is
 * to be constant within a major revision of the load balancing API, though new
 * algorithms may be created with a unique algorithm name within a given major
 * revision of the service API.
 *
 * Accepted options are:
 *
 * * LEAST_CONNECTIONS: The node with the lowest number of connections will
 *      receive requests.
 *
 * * RANDOM: Back-end servers are selected at random.
 *
 * * ROUND_ROBIN: Connections are routed to each of the back-end servers in turn.
 *
 * * WEIGHTED_LEAST_CONNECTIONS: Each request will be assigned to a node based
 *      on the number of concurrent connections to the node and its weight.
 *
 * * WEIGHTED_ROUND_ROBIN: A round robin algorithm, but with different
 *      proportions of traffic being directed to the back-end nodes. Weights
 *      must be defined as part of the load balancer's node configuration.
 */
class Algorithm extends ReadOnlyResource
{
    public $name;

    protected static $json_name = 'algorithm';
    protected static $url_resource = 'loadbalancers/algorithms';
}
