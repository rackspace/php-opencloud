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
use OpenCloud\Networking\Resource\Network;
use OpenCloud\Networking\Resource\Subnet;
use OpenCloud\Networking\Resource\Port;

/**
 * The Networking class represents the OpenNetwork Neutron service.
 *
 * Neutron is a service that provides networking between devices managed by other
 * OpenNetwork services (e.g. Compute).
 *
 * @codeCoverageIgnore
 */
class Service extends CatalogService
{
    const DEFAULT_TYPE = 'networks';
    const DEFAULT_NAME = 'cloudNetworks';

    /**
     * Returns a Network object associated with this Networking service
     *
     * @param string $id ID of network to retrieve
     * @return Network object
     */
    public function network($id = null)
    {
        return $this->resource('Network', $id);
    }

    /**
     * Creates a new Network and returns it.
     *
     * @param array $params Network creation parameters
     * @return Network Object representing created network
     */
    public function createNetwork($params = array())
    {
        $network = $this->network();
        $network->create($params);
        return $network;
    }

    /**
     * Returns a Network object associated with this Networking service
     *
     * @param string $id ID of network to retrieve
     * @return Network object
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
     * @return Subnet object
     */
    public function subnet($id = null)
    {
        return $this->resource('Subnet', $id);
    }

    /**
     * Creates a new Subnet and returns it.
     *
     * @param array $params Subnet creation parameters
     * @return Subnet Object representing created subnet
     */
    public function createSubnet($params = array())
    {
        $subnet = $this->subnet();
        $subnet->create($params);
        return $subnet;
    }

    /**
     * Returns a Subnet object associated with this Networking service
     *
     * @param string $id ID of subnet to retrieve
     * @return Subnet object
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
     * @return Port object
     */
    public function port($id = null)
    {
        return $this->resource('Port', $id);
    }

    /**
     * Creates a new Port and returns it.
     *
     * @param array $params Port creation parameters
     * @return Port Object representing created port
     */
    public function createPort($params = array())
    {
        $port = $this->port();
        $port->create($params);
        return $port;
    }

    /**
     * Returns a Port object associated with this Networking service
     *
     * @param string $id ID of port to retrieve
     * @return Port object
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
     * Return namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return array();
    }
}
