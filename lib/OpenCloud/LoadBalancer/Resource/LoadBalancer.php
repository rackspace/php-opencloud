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

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Resource\PersistentResource;
use OpenCloud\DNS\Resource\HasPtrRecordsInterface;

/**
 * A load balancer is a logical device which belongs to a cloud account. It is
 * used to distribute workloads between multiple back-end systems or services,
 * based on the criteria defined as part of its configuration.
 */
class LoadBalancer extends PersistentResource implements HasPtrRecordsInterface
{
    public $id;

    /**
     * Name of the load balancer to create. The name must be 128 characters or
     * less in length, and all UTF-8 characters are valid.
     *
     * @var string
     */
    public $name;

    /**
     * Port of the service which is being load balanced.
     *
     * @var string
     */
    public $port;

    /**
     * Protocol of the service which is being load balanced.
     *
     * @var string
     */
    public $protocol;

    /**
     * Type of virtual IP to add along with the creation of a load balancer.
     *
     * @var array|Collection
     */
    public $virtualIps = array();

    /**
     * Nodes to be added to the load balancer.
     *
     * @var array|Collection
     */
    public $nodes = array();

    /**
     * The access list management feature allows fine-grained network access
     * controls to be applied to the load balancer's virtual IP address.
     *
     * @var Collection
     */
    public $accessList;

    /**
     * Algorithm that defines how traffic should be directed between back-end nodes.
     *
     * @var Algorithm
     */
    public $algorithm;

    /**
     * Current connection logging configuration.
     *
     * @var ConnectionLogging
     */
    public $connectionLogging;

    /**
     * Specifies limits on the number of connections per IP address to help
     * mitigate malicious or abusive traffic to your applications.
     *
     * @var ConnectionThrottle
     */
    public $connectionThrottle;

    /**
     * The type of health monitor check to perform to ensure that the service is
     * performing properly.
     *
     * @var HealthMonitor
     */
    public $healthMonitor;

    /**
     * Forces multiple requests, of the same protocol, from clients to be
     * directed to the same node.
     *
     * @var SessionPersistance
     */
    public $sessionPersistence;

    /**
     * Information (metadata) that can be associated with each load balancer for
     * the client's personal use.
     *
     * @var array|Metadata
     */
    public $metadata = array();

    /**
     * The timeout value for the load balancer and communications with its nodes.
     * Defaults to 30 seconds with a maximum of 120 seconds.
     *
     * @var int
     */
    public $timeout;

    public $created;
    public $updated;
    public $status;
    public $nodeCount;
    public $sourceAddresses;
    public $cluster;

    protected static $json_name = 'loadBalancer';
    protected static $url_resource = 'loadbalancers';

    protected $associatedResources = array(
        'node'               => 'Node',
        'virtualIp'          => 'VirtualIp',
        'connectionLogging'  => 'ConnectionLogging',
        'healthMonitor'      => 'HealthMonitor',
        'sessionPersistance' => 'SessionPersistance'
    );

    protected $associatedCollections = array(
        'nodes'      => 'Node',
        'virtualIps' => 'VirtualIp',
        'accessList' => 'Access'
    );

    private $createKeys = array(
        'name',
        'port',
        'protocol',
        'virtualIps',
        'nodes',
        'accessList',
        'algorithm',
        'connectionLogging',
        'connectionThrottle',
        'healthMonitor',
        'sessionPersistence'
    );

    /**
     * This method creates a Node object and adds it to a list of Nodes
     * to be added to the LoadBalancer. *Very important:* this method *NEVER*
     * adds the nodes directly to the load balancer itself; it stores them
     * on the object, and the nodes are added later, in one of two ways:
     *
     * * for a new LoadBalancer, the Nodes are added as part of the Create()
     *   method call.
     * * for an existing LoadBalancer, you must call the AddNodes() method
     *
     * @api
     * @param string  $address   the IP address of the node
     * @param integer $port      the port # of the node
     * @param boolean $condition the initial condition of the node
     * @param string  $type      either PRIMARY or SECONDARY
     * @param integer $weight    the node weight (for round-robin)
     * @throws \OpenCloud\DomainError if value is not valid
     * @return void
     */
    public function addNode(
        $address,
        $port,
        $condition = 'ENABLED',
        $type = null,
        $weight = null
    )
    {
        $node = $this->Node();
        $node->address = $address;
        $node->port = $port;
        $cond = strtoupper($condition);

        switch ($cond) {
            case 'ENABLED':
            case 'DISABLED':
            case 'DRAINING':
                $node->condition = $cond;
                break;
            default:
                throw new Exceptions\DomainError(sprintf(
                    'Value [%s] for Node::condition is not valid',
                    $condition
                ));
        }

        if ($type !== null) {
            switch (strtoupper($type)) {
                case 'PRIMARY':
                case 'SECONDARY':
                    $node->type = $type;
                    break;
                default:
                    throw new Exceptions\DomainError(sprintf(
                        'Value [%s] for Node::type is not valid',
                        $type
                    ));
            }
        }

        if ($weight !== null) {
            if (is_integer($weight)) {
                $node->weight = $weight;
            } else {
                throw new Exceptions\DomainError(sprintf(
                    'Value [%s] for Node::weight must be integer',
                    $weight
                ));
            }
        }

        // queue it
        $this->nodes[] = $node;
    }

    public function addNodes()
    {
        if (count($this->nodes) < 1) {
            throw new Exceptions\MissingValueError(
                'Cannot add nodes; no nodes are defined'
            );
        }

        // iterate through all the nodes
        foreach ($this->nodes as $node) {
            $resp = $node->create();
        }

        return $resp;
    }

    /**
     * Remove a node from this load-balancer
     *
     * @api
     * @param int $id id of the node
     * @return mixed
     */
    public function removeNode($nodeId)
    {
        return $this->node($nodeId)->delete();
    }

    /**
     * adds a virtual IP to the load balancer
     *
     * You can use the strings `'PUBLIC'` or `'SERVICENET`' to indicate the
     * public or internal networks, or you can pass the `Id` of an existing
     * IP address.
     *
     * @api
     * @param string  $id        either 'public' or 'servicenet' or an ID of an
     *                           existing IP address
     * @param integer $ipVersion either null, 4, or 6 (both, IPv4, or IPv6)
     * @return void
     */
    public function addVirtualIp($type = 'PUBLIC', $ipVersion = null)
    {
        $object = new \stdClass();

        switch (strtoupper($type)) {
            case 'PUBLIC':
            case 'SERVICENET':
                $object->type = strtoupper($type);
                break;
            default:
                $object->id = $type;
                break;
        }

        if ($ipVersion) {
            switch ($ipVersion) {
                case 4:
                    $object->version = 'IPV4';
                    break;
                case 6:
                    $object->version = 'IPV6';
                    break;
                default:
                    throw new Exceptions\DomainError(sprintf(
                        'Value [%s] for ipVersion is not valid',
                        $ipVersion
                    ));
            }
        }

        /**
         * If the load balancer exists, we want to add it immediately.
         * If not, we add it to the virtualIps list and add it when the load
         * balancer is created.
         */
        if ($this->Id()) {
            $virtualIp = $this->virtualIp();
            $virtualIp->type = $type;
            $virtualIp->ipVersion = $object->version;

            return $virtualIp->create();
        } else {
            // queue it
            $this->virtualIps[] = $object;
        }

        return true;
    }

    /**
     * returns a Node object
     */
    public function node($id = null)
    {
        return $this->getService()->resource('Node', $id, $this);
    }

    /**
     * returns a Collection of Nodes
     */
    public function nodeList()
    {
        return $this->getService()->resourceList('Node', null, $this);
    }

    /**
     * returns a NodeEvent object
     */
    public function nodeEvent()
    {
        return $this->getService()->resource('NodeEvent', null, $this);
    }

    /**
     * returns a Collection of NodeEvents
     */
    public function nodeEventList()
    {
        return $this->getService()->resourceList('NodeEvent', null, $this);
    }

    /**
     * returns a single Virtual IP (not called publicly)
     */
    public function virtualIp($data = null)
    {
        return $this->getService()->resource('VirtualIp', $data, $this);
    }

    /**
     * returns  a Collection of Virtual Ips
     */
    public function virtualIpList()
    {
        return $this->getService()->resourceList('VirtualIp', null, $this);
    }

    /**
     */
    public function sessionPersistence()
    {
        return $this->getService()->resource('SessionPersistence', null, $this);
    }

    /**
     * returns the load balancer's error page object
     *
     * @api
     * @return ErrorPage
     */
    public function errorPage()
    {
        return $this->getService()->resource('ErrorPage', null, $this);
    }

    /**
     * returns the load balancer's health monitor object
     *
     * @api
     * @return HealthMonitor
     */
    public function healthMonitor()
    {
        return $this->getService()->resource('HealthMonitor', null, $this);
    }

    /**
     * returns statistics on the load balancer operation
     *
     * cannot be created, updated, or deleted
     *
     * @api
     * @return Stats
     */
    public function stats()
    {
        return $this->getService()->resource('Stats', null, $this);
    }

    /**
     */
    public function usage()
    {
        return $this->getService()->resourceList('UsageRecord', null, $this);
    }

    /**
     */
    public function access($data = null)
    {
        return $this->getService()->resource('Access', $data, $this);
    }

    /**
     */
    public function accessList()
    {
        return $this->getService()->resourceList('Access', null, $this);
    }

    /**
     */
    public function connectionThrottle()
    {
        return $this->getService()->resource('ConnectionThrottle', null, $this);
    }

    /**
     * Find out whether connection logging is enabled for this load balancer
     *
     * @return bool Returns TRUE if enabled, FALSE if not
     */
    public function hasConnectionLogging()
    {
        $url = clone $this->getUrl();
        $url->addPath('connectionlogging');

        $response = $this->getClient()->get($url)->send()->json();

        return isset($response['connectionLogging']['enabled'])
            && $response['connectionLogging']['enabled'] === true;
    }

    /**
     * Set the connection logging setting for this load balancer
     *
     * @param $bool  Set to TRUE to enable, FALSE to disable
     * @return mixed
     */
    public function setConnectionLogging($bool)
    {
        $url = clone $this->getUrl();
        $url->addPath('connectionlogging');

        $body = array('connectionLogging' => (bool) $bool);

        return $this->getClient()->put($url, self::getJsonHeader(), $body)->send();
    }

    /**
     * @deprecated
     */
    public function connectionLogging()
    {
        $this->getLogger()->deprecated(__METHOD__, 'hasConnectionLogging or setConnectionLogging');
    }

    /**
     * Find out whether content caching is enabled for this load balancer
     *
     * @return bool Returns TRUE if enabled, FALSE if not
     */
    public function hasContentCaching()
    {
        $url = clone $this->getUrl();
        $url->addPath('contentcaching');

        $response = $this->getClient()->get($url)->send()->json();

        return isset($response['contentCaching']['enabled'])
            && $response['contentCaching']['enabled'] === true;
    }

    /**
     * Set the content caching setting for this load balancer
     *
     * @param $bool  Set to TRUE to enable, FALSE to disable
     * @return mixed
     */
    public function setContentCaching($bool)
    {
        $url = clone $this->getUrl();
        $url->addPath('contentcaching');

        $body = array('contentcaching' => (bool) $bool);

        return $this->getClient()->put($url, self::getJsonHeader(), $body)->send();
    }

    /**
     * @deprecated
     */
    public function contentCaching()
    {
        $this->getLogger()->deprecated(__METHOD__, 'hasContentCaching or setContentCaching');
    }

    /**
     */
    public function SSLTermination()
    {
        return $this->getService()->resource('SSLTermination', null, $this);
    }

    /**
     */
    public function metadata($data = null)
    {
        return $this->getService()->resource('Metadata', $data, $this);
    }

    /**
     */
    public function metadataList()
    {
        return $this->getService()->resourceList('Metadata', null, $this);
    }

    /**
     * returns the JSON object for Create()
     *
     * @return stdClass
     */
    protected function createJson()
    {
        $element = (object)array();

        foreach ($this->createKeys as $key) {
            if ($key == 'nodes') {
                foreach ($this->nodes as $node) {
                    $nodeObject = (object)array();
                    foreach ($node->createKeys as $key) {
                        if (!empty($node->$key)) {
                            $nodeObject->$key = $node->$key;
                        }
                    }
                    $element->nodes[] = (object)$nodeObject;
                }
            } elseif ($key == 'virtualIps') {
                foreach ($this->virtualIps as $virtualIp) {
                    $element->virtualIps[] = $virtualIp;
                }
            } elseif (isset($this->$key)) {
                $element->$key = $this->$key;
            }
        }

        $object = (object)array($this->jsonName() => $element);

        return $object;
    }

    /**
     * returns the JSON object for Update()
     *
     * @return stdClass
     * @throws \OpenCloud\Common\Exceptions\InvalidParameterError
     */
    protected function updateJson($params = array())
    {
        $updatableFields = array('name', 'algorithm', 'protocol', 'port', 'timeout', 'halfClosed');

        //Validate supplied fields
        $fields = array_keys($params);
        foreach ($fields as $field) {
            if (!in_array($field, $updatableFields)) {
                throw new Exceptions\InvalidArgumentError("You cannot update $field.");
            }
        }

        $object = new \stdClass();
        $object->loadBalancer = new \stdClass();
        foreach ($params as $name => $value) {
            $object->loadBalancer->$name = $this->$name;
        }

        return $object;
    }
}
