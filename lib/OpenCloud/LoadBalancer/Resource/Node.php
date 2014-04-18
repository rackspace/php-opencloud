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

use OpenCloud\Common\Resource\PersistentResource;

/**
 * The nodes defined by the load balancer are responsible for servicing the
 * requests received through the load balancer's virtual IP. By default, the
 * load balancer employs a basic health check that ensures the node is listening
 * on its defined port. The node is checked at the time of addition and at regular
 * intervals as defined by the load balancer health check configuration. If a
 * back-end node is not listening on its port or does not meet the conditions of
 * the defined active health check for the load balancer, then the load balancer
 * will not forward connections and its status will be listed as "OFFLINE". Only
 * nodes that are in an "ONLINE" status will receive and be able to service
 * traffic from the load balancer.
 *
 * All nodes have an associated status that indicates whether the node is
 * ONLINE, OFFLINE, or DRAINING. Only nodes that are in ONLINE status will
 * receive and be able to service traffic from the load balancer. The OFFLINE
 * status represents a node that cannot accept or service traffic. A node in
 * DRAINING status represents a node that stops the traffic manager from sending
 * any additional new connections to the node, but honors established sessions.
 * If the traffic manager receives a request and session persistence requires
 * that the node is used, the traffic manager will use it. The status is
 * determined by the passive or active health monitors.
 *
 * If the WEIGHTED_ROUND_ROBIN load balancer algorithm mode is selected, then
 * the caller should assign the relevant weights to the node as part of the
 * weight attribute of the node element. When the algorithm of the load balancer
 * is changed to WEIGHTED_ROUND_ROBIN and the nodes do not already have an
 * assigned weight, the service will automatically set the weight to "1" for all nodes.
 *
 * One or more secondary nodes can be added to a specified load balancer so that
 * if all the primary nodes fail, traffic can be redirected to secondary nodes.
 * The type attribute allows configuring the node as either PRIMARY or SECONDARY.
 */
class Node extends PersistentResource
{
    public $id;

    /**
     * IP address or domain name for the node.
     *
     * @var string
     */
    public $address;

    /**
     * Port number for the service you are load balancing.
     *
     * @var int
     */
    public $port;

    /**
     * Condition for the node, which determines its role within the load balancer.
     *
     * @var string
     */
    public $condition;

    /**
     * Current state of the node. Can either be ONLINE, OFFLINE or DRAINING.
     *
     * @var string
     */
    public $status;

    /**
     * Weight of node to add. If the WEIGHTED_ROUND_ROBIN load balancer algorithm
     * mode is selected, then the user should assign the relevant weight to the
     * node using the weight attribute for the node. Must be an integer from 1 to 100.
     *
     * @var int
     */
    public $weight;

    /**
     * Type of node to add:
     *
     * * PRIMARY: Nodes defined as PRIMARY are in the normal rotation to receive
     *      traffic from the load balancer.
     *
     * * SECONDARY: Nodes defined as SECONDARY are only in the rotation to
     *      receive traffic from the load balancer when all the primary nodes fail.
     *
     * @var string
     */
    public $type;

    protected static $json_name = false;
    protected static $json_collection_name = 'nodes';
    protected static $url_resource = 'nodes';

    public $createKeys = array(
        'address',
        'port',
        'condition',
        'type',
        'weight'
    );

    /**
     * returns the Node name
     *
     * @return string
     */
    public function name()
    {
        return get_class() . '[' . $this->Id() . ']';
    }

    public function createJson()
    {
        $nodes = array('node' => array());

        foreach ($this->createKeys as $key) {
            $nodes['node'][$key] = $this->$key;
        }

        return array('nodes' => array($nodes));
    }

    protected function updateJson($params = array())
    {
        if ($this->condition) {
            $params['condition'] = $this->condition;
        }
        if ($this->type) {
            $params['type'] = $this->type;
        }
        if ($this->weight) {
            $params['weight'] = $this->weight;
        }

        return (object) array('node' => (object) $params);
    }

    /**
     * Returns a Metadata item
     *
     * @return Metadata
     */
    public function metadata($data = null)
    {
        return $this->getService()->resource('Metadata', $data, $this);
    }

    /**
     * Returns a paginated collection of metadata
     *
     * @return PaginatedIterator
     */
    public function metadataList()
    {
        return $this->getService()->resourceList('Metadata', null, $this);
    }
}
