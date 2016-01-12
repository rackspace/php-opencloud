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
use OpenCloud\Common\Log\Logger;
use OpenCloud\Common\Resource\PersistentResource;
use OpenCloud\DNS\Resource\HasPtrRecordsInterface;
use OpenCloud\LoadBalancer\Enum\NodeCondition;
use OpenCloud\LoadBalancer\Enum\IpType;
use OpenCloud\LoadBalancer\Enum\NodeType;

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
     * Enables or disables HTTP to HTTPS redirection for the load balancer.
     *
     * @var bool
     */
    public $httpsRedirect;

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
        'certificateMapping' => 'CertificateMapping',
        'node'               => 'Node',
        'virtualIp'          => 'VirtualIp',
        'connectionLogging'  => 'ConnectionLogging',
        'healthMonitor'      => 'HealthMonitor',
        'sessionPersistance' => 'SessionPersistance'
    );

    protected $associatedCollections = array(
        'certificateMappings' => 'CertificateMapping',
        'nodes'               => 'Node',
        'virtualIps'          => 'VirtualIp',
        'accessList'          => 'Access'
    );

    protected $createKeys = array(
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
        'sessionPersistence',
        'httpsRedirect'
    );

    /**
     * This method creates a Node object and adds it to a list of Nodes
     * to be added to the LoadBalancer. This method will not add the nodes
     * directly to the load balancer itself; it stores them in an array and
     * the nodes are added later, in one of two ways:
     *
     * * for a new load balancer, the nodes are added as part of the create() method call
     * * for an existing load balancer, you must call the addNodes() method
     *
     * @param string  $address   the IP address of the node
     * @param integer $port      the port # of the node
     * @param boolean $condition the initial condition of the node
     * @param string  $type      either PRIMARY or SECONDARY
     * @param integer $weight    the node weight (for round-robin)
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function addNode(
        $address,
        $port,
        $condition = NodeCondition::ENABLED,
        $type = null,
        $weight = null
    ) {
        $allowedConditions = array(
            NodeCondition::ENABLED,
            NodeCondition::DISABLED,
            NodeCondition::DRAINING
        );

        if (!in_array($condition, $allowedConditions)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid condition. It must one of the following: %s",
                implode(', ', $allowedConditions)
            ));
        }

        $allowedTypes = array(NodeType::PRIMARY, NodeType::SECONDARY);
        if ($type && !in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid type. It must one of the following: %s",
                implode(', ', $allowedTypes)
            ));
        }

        if ($weight && !is_numeric($weight)) {
            throw new \InvalidArgumentException('Invalid weight. You must supply a numeric type');
        }

        // queue it
        $this->nodes[] = $this->node(array(
            'address'   => $address,
            'port'      => $port,
            'condition' => $condition,
            'type'      => $type,
            'weight'    => $weight
        ));
    }

    /**
     * Creates currently added nodes by sending them to the API
     *
     * @return array of {@see \Guzzle\Http\Message\Response} objects
     * @throws \OpenCloud\Common\Exceptions\MissingValueError
     */
    public function addNodes()
    {
        if (empty($this->nodes)) {
            throw new Exceptions\MissingValueError(
                'Cannot add nodes; no nodes are defined'
            );
        }

        $requestData = array('nodes' => array());

        /** @var Node $node */
        foreach ($this->nodes as $node) {
            // Only add the node if it is new
            if (null === $node->getId()) {
                $nodeJson = $node->createJson();
                $requestData['nodes'][] = $nodeJson['nodes'][0];
            }
        }

        $request = $this->getClient()->post($node->getUrl(), self::getJsonHeader(), json_encode($requestData));

        return $this->getClient()->send($request);
    }

    /**
     * Remove a node from this load-balancer
     *
     * @param int $id id of the node
     * @return \Guzzle\Http\Message\Response
     */
    public function removeNode($nodeId)
    {
        return $this->node($nodeId)->delete();
    }

    /**
     * Adds a virtual IP to the load balancer. You can use the strings 'PUBLIC'
     * or 'SERVICENET' to indicate the public or internal networks, or you can
     * pass the `Id` of an existing IP address.
     *
     * @param string  $id        either 'public' or 'servicenet' or an ID of an
     *                           existing IP address
     * @param integer $ipVersion either null, 4, or 6 (both, IPv4, or IPv6)
     * @return void
     */
    public function addVirtualIp($type = IpType::PUBLIC_TYPE, $ipVersion = null)
    {
        $object = new \stdClass();

        switch (strtoupper($type)) {
            case IpType::PUBLIC_TYPE:
            case IpType::SERVICENET_TYPE:
                $object->type = strtoupper($type);
                break;
            default:
                $object->id = $type;
                break;
        }

        if ($ipVersion) {
            switch ($ipVersion) {
                case 4:
                    $object->ipVersion = IpType::IPv4;
                    break;
                case 6:
                    $object->ipVersion = IpType::IPv6;
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
            $virtualIp->ipVersion = $object->ipVersion;
            return $virtualIp->create();
        } else {
            // queue it
            $this->virtualIps[] = $object;
        }

        return true;
    }

    /**
     * Returns a Node
     *
     * @return \OpenCloud\LoadBalancer\Resource\Node
     */
    public function node($id = null)
    {
        return $this->getService()->resource('Node', $id, $this);
    }

    /**
     * returns a Collection of Nodes
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function nodeList()
    {
        return $this->getService()->resourceList('Node', null, $this);
    }

    /**
     * Returns a NodeEvent object
     *
     * @return \OpenCloud\LoadBalancer\Resource\NodeEvent
     */
    public function nodeEvent()
    {
        return $this->getService()->resource('NodeEvent', null, $this);
    }

    /**
     * Returns a Collection of NodeEvents
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function nodeEventList()
    {
        return $this->getService()->resourceList('NodeEvent', null, $this);
    }

    /**
     * Returns a single Virtual IP (not called publicly)
     *
     * @return \OpenCloud\LoadBalancer\Resource\VirtualIp
     */
    public function virtualIp($data = null)
    {
        return $this->getService()->resource('VirtualIp', $data, $this);
    }

    /**
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function virtualIpList()
    {
        return $this->getService()->resourceList('VirtualIp', null, $this);
    }

    /**
     * Returns a Certificate Mapping.
     *
     * @param int|array $id (Optional) Either a particular Certificate mapping ID, or an array of data about the
     *                      mapping. An array can include these keys: hostName, privateKey, certificate,
     *                      intermediateCertificate.
     * @return \OpenCloud\LoadBalancer\Resource\CertificateMapping
     */
    public function certificateMapping($id = null)
    {
        return $this->getService()->resource('CertificateMapping', $id, $this);
    }

    /**
     * Returns a Collection of Certificate Mappings.
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function certificateMappingList()
    {
        return $this->getService()->resourceList('CertificateMapping', null, $this);
    }

    /**
     * Creates a certificate mapping.
     *
     * @throws \OpenCloud\Common\Exceptions\MissingValueError
     *
     * @param string $hostName                The domain name for the certificate.
     * @param string $privateKey              The private key for the certificate
     * @param string $certificate             The certificate itself.
     * @param string $intermediateCertificate The intermediate certificate chain.
     * @return array An array of \Guzzle\Http\Message\Response objects.
     */
    public function addCertificateMapping(
        $hostName,
        $privateKey,
        $certificate,
        $intermediateCertificate = null
    ) {
        $certificateMapping = $this->certificateMapping(
            array(
                'hostName'                => $hostName,
                'privateKey'              => $privateKey,
                'certificate'             => $certificate,
                'intermediateCertificate' => $intermediateCertificate
            )
        );
        $json = json_encode($certificateMapping->createJson());
        $request = $this->getClient()->post($certificateMapping->getUrl(), self::getJsonHeader(), $json);

        return $this->getClient()->send($request);
    }

    /**
     * Updates a certificate mapping.
     *
     * @param int    $id                      ID of the certificate mapping.
     * @param string $hostName                (Optional) The domain name of the certificate.
     * @param string $privateKey              (Optional) The private key for the certificate.
     * @param string $certificate             The certificate itself.
     * @param string $intermediateCertificate The intermediate certificate chain.
     * @return array An array of \Guzzle\Http\Message\Response objects.
     */
    public function updateCertificateMapping(
        $id,
        $hostName = null,
        $privateKey = null,
        $certificate = null,
        $intermediateCertificate = null
    ) {
        $certificateMapping = $this->certificateMapping($id);
        return $certificateMapping->update(
            array(
                'hostName'                => $hostName,
                'privateKey'              => $privateKey,
                'certificate'             => $certificate,
                'intermediateCertificate' => $intermediateCertificate
            )
        );
    }

    /**
     * Remove a certificate mapping.
     *
     * @param int $id ID of the certificate mapping.
     * @return \Guzzle\Http\Message\Response
     */
    public function removeCertificateMapping($id)
    {
        return $this->certificateMapping($id)->delete();
    }

    /**
     * Return the session persistence resource
     *
     * @return \OpenCloud\LoadBalancer\Resource\SessionPersistence
     */
    public function sessionPersistence()
    {
        return $this->getService()->resource('SessionPersistence', null, $this);
    }

    /**
     * Returns the load balancer's error page object
     *
     * @return \OpenCloud\LoadBalancer\Resource\ErrorPage
     */
    public function errorPage()
    {
        return $this->getService()->resource('ErrorPage', null, $this);
    }

    /**
     * Returns the load balancer's health monitor object
     *
     * @return \OpenCloud\LoadBalancer\Resource\HealthMonitor
     */
    public function healthMonitor()
    {
        return $this->getService()->resource('HealthMonitor', null, $this);
    }

    /**
     * Returns statistics on the load balancer operation
     *
     * @return \OpenCloud\LoadBalancer\Resource\Stats
     */
    public function stats()
    {
        return $this->getService()->resource('Stats', null, $this);
    }

    /**
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function usage()
    {
        return $this->getService()->resourceList('UsageRecord', null, $this);
    }

    /**
     * Return an access resource
     *
     * @return \OpenCloud\LoadBalancer\Resource\Access
     */
    public function access($data = null)
    {
        return $this->getService()->resource('Access', $data, $this);
    }

    /**
     * Creates an access list. You must provide an array of \stdClass objects,
     * each of which contains `type' and `address' properties. Valid types for
     * the former are: "DENY" or "ALLOW". The address must be a valid IP
     * address, either v4 or v6.
     *
     * @param stdClass[] $list
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function createAccessList(array $list)
    {
        $url = $this->getUrl();
        $url->addPath('accesslist');

        $json = json_encode($list);
        $this->checkJsonError();

        return $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
    }

    /**
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function accessList()
    {
        return $this->getService()->resourceList('Access', null, $this);
    }

    /**
     * Return a connection throttle resource
     *
     * @return \OpenCloud\LoadBalancer\Resource\ConnectionThrottle
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
     * @return \Guzzle\Http\Message\Response
     */
    public function enableConnectionLogging($bool)
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
        $this->getLogger()->warning(Logger::deprecated(__METHOD__, 'hasConnectionLogging or enableConnectionLogging'));
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
     * @return \Guzzle\Http\Message\Response
     */
    public function enableContentCaching($bool)
    {
        $url = clone $this->getUrl();
        $url->addPath('contentcaching');

        $body = array('contentCaching' => array('enabled' => (bool) $bool));
        $body = json_encode($body);
        $this->checkJsonError();

        return $this->getClient()->put($url, self::getJsonHeader(), $body)->send();
    }

    /**
     * @deprecated
     */
    public function contentCaching()
    {
        $this->getLogger()->warning(sprintf(
            'The %s method is deprecated, please use %s instead', __METHOD__, 'hasContentCaching or setContentCaching'));
    }

    /**
     * Return a SSL Termination resource
     *
     * @return \OpenCloud\LoadBalancer\Resource\SSLTermination
     */
    public function SSLTermination()
    {
        return $this->getService()->resource('SSLTermination', null, $this);
    }

    /**
     * Return a metadata item
     *
     * @return \OpenCloud\LoadBalancer\Resource\Metadata
     */
    public function metadata($data = null)
    {
        return $this->getService()->resource('Metadata', $data, $this);
    }

    /**
     * Return a collection of metadata items
     *
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function metadataList()
    {
        return $this->getService()->resourceList('Metadata', null, $this);
    }

    protected function createJson()
    {
        $element = (object) array();

        foreach ($this->createKeys as $key) {
            if ($key == 'nodes') {
                foreach ($this->nodes as $node) {
                    $nodeObject = (object) array();
                    foreach ($node->createKeys as $key) {
                        if (!empty($node->$key)) {
                            $nodeObject->$key = $node->$key;
                        }
                    }
                    $element->nodes[] = (object) $nodeObject;
                }
            } elseif ($key == 'virtualIps') {
                foreach ($this->virtualIps as $virtualIp) {
                    $element->virtualIps[] = $virtualIp;
                }
            } elseif (isset($this->$key)) {
                $element->$key = $this->$key;
            }
        }

        $object = (object) array($this->jsonName() => $element);

        return $object;
    }

    protected function updateJson($params = array())
    {
        $updatableFields = array('name', 'algorithm', 'protocol', 'port', 'timeout', 'halfClosed', 'httpsRedirect');

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
