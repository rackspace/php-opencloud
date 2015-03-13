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

namespace OpenCloud\Networking;

use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Networking\Resource\Network;
use OpenCloud\Networking\Resource\Subnet;
use OpenCloud\Networking\Resource\Port;
use OpenCloud\Networking\Resource\SecurityGroup;
use OpenCloud\Networking\Resource\SecurityGroupRule;

/**
 * The Networking class represents the OpenNetwork Neutron service.
 *
 * Neutron is a service that provides networking between devices managed by other
 * OpenNetwork services (e.g. Compute).
 */
class Service extends CatalogService
{
    const SUPPORTED_VERSION = 'v2.0';
    const DEFAULT_TYPE = 'network';
    const DEFAULT_NAME = 'cloudNetworks';

    /**
     * Returns a Network object associated with this Networking service
     *
     * @param string $id ID of network to retrieve
     * @return \OpenCloud\Networking\Resource\Network object
     */
    public function network($id = null)
    {
        return $this->resource('Network', $id);
    }

    /**
     * Creates a new Network and returns it.
     *
     * @param array $params Network creation parameters.
     * @return \OpenCloud\Networking\Resource\Network Object representing created network
     *
     * @see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/Networking/USERGUIDE.md#create-a-network
     */
    public function createNetwork(array $params = array())
    {
        $network = $this->network();
        $network->create($params);
        return $network;
    }

    /**
     * Creates multiple new Networks and returns their list.
     *
     * @param array $networksParams Array of network creation parameters' arrays
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function createNetworks(array $networksParams = array())
    {
        // Form URL
        $url = clone $this->getUrl();
        $url->addPath(Network::resourceName());

        // Form JSON
        $singleNetworkJsonName = Network::jsonName();
        $networksJsonCollectionName = Network::jsonCollectionName();
        $networks = array();
        foreach ($networksParams as $networkParams) {
            $network = $this->network();
            $network->populate($networkParams);
            $networks[] = $network->createJson()->{$singleNetworkJsonName};
        }
        $json = json_encode(array(
            $networksJsonCollectionName => $networks
        ));

        // Call the API
        $response = $this->getClient()->post($url, self::getJsonHeader(), $json)->send();

        // Parse the response into a collection of created networks
        $responseJson = Formatter::decode($response);
        $createdNetworksJson = $responseJson->{$networksJsonCollectionName};

        // Return collection of created networks
        return $this->collection('Network', $url, $this, $createdNetworksJson);
    }

    /**
     * Returns a Network object associated with this Networking service
     *
     * @param string $id ID of network to retrieve
     * @return \OpenCloud\Networking\Resource\Network object
     */
    public function getNetwork($id)
    {
        return $this->network($id);
    }

    /**
     * Returns a list of networks you created
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listNetworks(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Network::resourceName())->setQuery($params);

        return $this->resourceList('Network', $url);
    }

    /**
     * Returns a Subnet object associated with this Networking service
     *
     * @param string $id ID of subnet to retrieve
     * @return \OpenCloud\Networking\Resource\Subnet object
     */
    public function subnet($id = null)
    {
        return $this->resource('Subnet', $id);
    }

    /**
     * Creates a new Subnet and returns it.
     *
     * @param array $params Subnet creation parameters.
     * @return \OpenCloud\Networking\Resource\Subnet Object representing created subnet
     *
     * @see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/Networking/USERGUIDE.md#create-a-subnet
     */
    public function createSubnet(array $params = array())
    {
        $subnet = $this->subnet();
        $subnet->create($params);
        return $subnet;
    }

    /**
     * Creates multiple new Subnets and returns their list.
     *
     * @param array $subnetsParams Array of subnet creation parameters' arrays
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function createSubnets(array $subnetsParams = array())
    {
        // Form URL
        $url = clone $this->getUrl();
        $url->addPath(Subnet::resourceName());

        // Form JSON
        $singleSubnetJsonName = Subnet::jsonName();
        $subnetsJsonCollectionName = Subnet::jsonCollectionName();
        $subnets = array();
        foreach ($subnetsParams as $subnetParams) {
            $subnet = $this->subnet();
            $subnet->populate($subnetParams);
            $subnets[] = $subnet->createJson()->{$singleSubnetJsonName};
        }
        $json = json_encode(array(
            $subnetsJsonCollectionName => $subnets
        ));

        // Call the API
        $response = $this->getClient()->post($url, self::getJsonHeader(), $json)->send();

        // Parse the response into a collection of created subnets
        $responseJson = Formatter::decode($response);
        $createdSubnetsJson = $responseJson->{$subnetsJsonCollectionName};

        // Return collection of created subnets
        return $this->collection('Subnet', $url, $this, $createdSubnetsJson);
    }

    /**
     * Returns a Subnet object associated with this Networking service
     *
     * @param string $id ID of subnet to retrieve
     * @return \OpenCloud\Networking\Resource\Subnet object
     */
    public function getSubnet($id)
    {
        return $this->subnet($id);
    }

    /**
     * Returns a list of subnets you created
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listSubnets(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Subnet::resourceName())->setQuery($params);

        return $this->resourceList('Subnet', $url);
    }

    /**
     * Returns a Port object associated with this Networking service
     *
     * @param string $id ID of port to retrieve
     * @return \OpenCloud\Networking\Resource\Port object
     */
    public function port($id = null)
    {
        return $this->resource('Port', $id);
    }

    /**
     * Creates a new Port and returns it.
     *
     * @param array $params Port creation parameters.
     * @return \OpenCloud\Networking\Resource\Port Object representing created port
     *
     * @see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/Networking/USERGUIDE.md#create-a-port
     */
    public function createPort(array $params = array())
    {
        $port = $this->port();
        $port->create($params);
        return $port;
    }

    /**
     * Creates multiple new Ports and returns their list.
     *
     * @param array $portsParams Array of port creation parameters' arrays
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function createPorts(array $portsParams = array())
    {
        // Form URL
        $url = clone $this->getUrl();
        $url->addPath(Port::resourceName());

        // Form JSON
        $singlePortJsonName = Port::jsonName();
        $portsJsonCollectionName = Port::jsonCollectionName();
        $ports = array();
        foreach ($portsParams as $portParams) {
            $port = $this->port();
            $port->populate($portParams);
            $ports[] = $port->createJson()->{$singlePortJsonName};
        }
        $json = json_encode(array(
            $portsJsonCollectionName => $ports
        ));

        // Call the API
        $response = $this->getClient()->post($url, self::getJsonHeader(), $json)->send();

        // Parse the response into a collection of created ports
        $responseJson = Formatter::decode($response);
        $createdPortsJson = $responseJson->{$portsJsonCollectionName};

        // Return collection of created ports
        return $this->collection('Port', $url, $this, $createdPortsJson);
    }

    /**
     * Returns a Port object associated with this Networking service
     *
     * @param string $id ID of port to retrieve
     * @return \OpenCloud\Networking\Resource\Port object
     */
    public function getPort($id)
    {
        return $this->port($id);
    }

    /**
     * Returns a list of ports you created
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listPorts(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Port::resourceName())->setQuery($params);

        return $this->resourceList('Port', $url);
    }

    /**
     * Returns a SecurityGroup object associated with this Networking service
     *
     * @param string $id ID of security group to retrieve
     * @return \OpenCloud\Networking\Resource\SecurityGroup object
     */
    public function securityGroup($id = null)
    {
        return $this->resource('SecurityGroup', $id);
    }

    /**
     * Creates a new SecurityGroup and returns it.
     *
     * @param array $params SecurityGroup creation parameters.
     * @return \OpenCloud\Networking\Resource\SecurityGroup Object representing created security group
     *
     * @see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/Networking/USERGUIDE.md#create-a-security-group
     */
    public function createSecurityGroup(array $params = array())
    {
        $securityGroup = $this->securityGroup();
        $securityGroup->create($params);
        return $securityGroup;
    }

    /**
     * Returns a SecurityGroup object associated with this Networking service
     *
     * @param string $id ID of security group to retrieve
     * @return \OpenCloud\Networking\Resource\SecurityGroup object
     */
    public function getSecurityGroup($id)
    {
        return $this->securityGroup($id);
    }

    /**
     * Returns a list of security groups you created
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listSecurityGroups(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(SecurityGroup::resourceName())->setQuery($params);

        return $this->resourceList('SecurityGroup', $url);
    }

    /**
     * Returns a SecurityGroupRule object associated with this Networking service
     *
     * @param string $id ID of security group rule to retrieve
     * @return \OpenCloud\Networking\Resource\SecurityGroupRule object
     */
    public function securityGroupRule($id = null)
    {
        return $this->resource('SecurityGroupRule', $id);
    }

    /**
     * Creates a new SecurityGroupRule and returns it.
     *
     * @param array $params SecurityGroupRule creation parameters.
     * @return \OpenCloud\Networking\Resource\SecurityGroupRule Object representing created security group rule
     *
     * @see https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/Networking/USERGUIDE.md#create-a-security-group-rule
     */
    public function createSecurityGroupRule(array $params = array())
    {
        $securityGroupRule = $this->securityGroupRule();
        $securityGroupRule->create($params);
        return $securityGroupRule;
    }

    /**
     * Returns a SecurityGroupRule object associated with this Networking service
     *
     * @param string $id ID of security group rule to retrieve
     * @return \OpenCloud\Networking\Resource\SecurityGroupRule object
     */
    public function getSecurityGroupRule($id)
    {
        return $this->securityGroupRule($id);
    }

    /**
     * Returns a list of security group rules you created
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listSecurityGroupRules(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(SecurityGroupRule::resourceName())->setQuery($params);

        return $this->resourceList('SecurityGroupRule', $url);
    }

    /**
     * Return namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return array();
    }
}
