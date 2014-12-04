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

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Utils;

/**
 * Description of Networking
 *
 * @link
 */
class Networking extends AbstractUnit implements UnitInterface
{
    protected $cleanupNetworkIds = array();
    protected $cleanupSubnetIds  = array();
    protected $cleanupPortIds    = array();

    public function setupService()
    {
        return $this->getConnection()->networkingService('cloudNetworks', Utils::getRegion());
    }

    public function main()
    {
        $this->testNetworkOperations();
        $this->testSubnetOperations();
        $this->testPortOperations();
    }

    protected function testNetworkOperations()
    {
        $this->step('Create network');
        $createdNetwork = $this->getService()->createNetwork(array(
            'name' => 'test_network'
        ));
        $this->stepInfo('Network ID: ' . $createdNetwork->getId());
        $this->stepInfo('Network Name: ' . $createdNetwork->getName());
        $this->cleanupNetworkIds[] = $createdNetwork->getId();

        // The next operation is commented out (for now) because the Rackspace
        // Networking API does not support bulk operations (for now). When that
        // changes in the future, please uncomment this operation.
        // $this->step('Create networks');
        // $createdNetworks = $this->getService()->createNetworks(array(
        //     array( 'name' => 'test_network_1' ),
        //     array( 'name' => 'test_network_2' ),
        //     array( 'name' => 'test_network_3' ),
        // ));
        // $this->stepInfo('%-40s | %s', 'Network ID', 'Network name');
        // $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        // foreach ($createdNetworks as $network) {
        //     $this->stepInfo('%-40s | %s', $network->getId(), $network->getName());
        //     $this->cleanupNetworkIds[] = $network->getId();
        // }

        $this->step('List networks');
        $networks = $this->getService()->listNetworks();
        $this->stepInfo('%-40s | %s', 'Network ID', 'Network name');
        $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        foreach ($networks as $network) {
            $this->stepInfo('%-40s | %s', $network->getId(), $network->getName());
        }

        $this->step('Get network');
        $network = $this->getService()->getNetwork($createdNetwork->getId());
        $this->stepInfo('Network ID: ' . $network->getId());
        $this->stepInfo('Network Name: ' . $network->getName());

        $this->step('Update network');
        $network->update(array(
            'name' => 'updated_test_network'
        ));
    }

    protected function testSubnetOperations()
    {
        $network1 = $this->getService()->createNetwork(array(
            'name' => 'test_network_for_test_subnet'
        ));
        $this->cleanupNetworkIds[] = $network1->getId();

        $this->step('Create subnet');
        $subnet = $this->getService()->createSubnet(array(
            'cidr'      => '192.168.199.0/24',
            'networkId' => $network1->getId(),
            'ipVersion' => 4,
            'name'      => 'test_subnet'
        ));
        $this->stepInfo('Subnet ID: ' . $subnet->getId());
        $this->stepInfo('Subnet Name: ' . $subnet->getName());
        $this->cleanupSubnetIds[] = $subnet->getId();

        $network2 = $this->getService()->createNetwork(array(
            'name' => 'test_network_for_test_subnet_w_gateway'
        ));
        $this->cleanupNetworkIds[] = $network2->getId();

        $this->step('Create subnet with gateway IP');
        $subnet = $this->getService()->createSubnet(array(
            'cidr'      => '192.168.62.0/25',
            'networkId' => $network2->getId(),
            'ipVersion' => 4,
            'name'      => 'test_subnet_with_gateway_ip',
            'gatewayIp' => '192.168.62.128'
        ));
        $this->stepInfo('Subnet ID: ' . $subnet->getId());
        $this->stepInfo('Subnet Name: ' . $subnet->getName());
        $this->cleanupSubnetIds[] = $subnet->getId();

        $network3 = $this->getService()->createNetwork(array(
            'name' => 'test_network_for_test_subnet_w_host_rt'
        ));
        $this->cleanupNetworkIds[] = $network3->getId();

        $this->step('Create subnet with host routes');
        $subnet = $this->getService()->createSubnet(array(
            'cidr'       => '192.168.62.0/25',
            'networkId'  => $network3->getId(),
            'ipVersion'  => 4,
            'name'       => 'test_subnet_with_host_routes',
            'hostRoutes' => array(
                array(
                    'destination' => '1.1.1.0/24',
                    'nexthop'     => '192.168.19.20'
                )
            )
        ));
        $this->stepInfo('Subnet ID: ' . $subnet->getId());
        $this->stepInfo('Subnet Name: ' . $subnet->getName());
        $this->cleanupSubnetIds[] = $subnet->getId();

        // The next operation is commented out (for now) because the Rackspace
        // Networking API does not support bulk operations (for now). When that
        // changes in the future, please uncomment this operation.
        // $this->step('Create subnets');
        // $subnets = $this->getService()->createSubnets(array(
        //     array(
        //         'cidr'      => '192.168.199.0/24',
        //         'networkId' => $network->getId(),
        //         'ipVersion' => 4,
        //         'name'      => 'test_subnet_1'
        //     )
        // ));
        // $this->stepInfo('%-40s | %s', 'Subnet ID', 'Subnet name');
        // $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        // foreach ($subnets as $subnet) {
        //     $this->stepInfo('%-40s | %s', $subnet->getId(), $subnet->getName());
        //     $this->cleanupSubnetIds[] = $subnet->getId();
        // }

        $this->step('List subnets');
        $subnets = $this->getService()->listSubnets();
        $this->stepInfo('%-40s | %s', 'Subnet ID', 'Subnet name');
        $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        foreach ($subnets as $subnet) {
            $this->stepInfo('%-40s | %s', $subnet->getId(), $subnet->getName());
        }

        $this->step('Get subnet');
        $subnet = $this->getService()->getSubnet($subnet->getId());
        $this->stepInfo('Subnet ID: ' . $subnet->getId());
        $this->stepInfo('Subnet Name: ' . $subnet->getName());

        $this->step('Update subnet');
        $subnet->update(array(
            'name' => 'updated_test_subnet',
            'hostRoutes' => array(
                array(
                    'destination' => '1.1.1.0/24',
                    'nexthop'     => '192.168.17.19'
                )
            ),
            'gatewayIp' => '192.168.62.155'
        ));
    }

    protected function testPortOperations()
    {
        $network1 = $this->getService()->createNetwork(array(
            'name' => 'test_network_for_test_port'
        ));
        $this->cleanupNetworkIds[] = $network1->getId();

        $subnet1 = $this->getService()->createSubnet(array(
            'cidr'      => '192.168.62.0/25',
            'networkId' => $network1->getId(),
            'ipVersion'  => 4,
            'name'      => 'test_subnet_for_test_port'
        ));
        $this->cleanupSubnetIds[] = $subnet1->getId();

        $this->step('Create port');
        $port = $this->getService()->createPort(array(
            'networkId' => $network1->getId(),
            'name'      => 'test_port'
        ));
        $this->stepInfo('Port ID: ' . $port->getId());
        $this->stepInfo('Port Name: ' . $port->getName());
        $this->cleanupPortIds[] = $port->getId();

        // The next operation is commented out (for now) because the Rackspace
        // Networking API does not support bulk operations (for now). When that
        // changes in the future, please uncomment this operation.
        // $this->step('Create ports');
        // $ports = $this->getService()->createPorts(array(
        // ));
        // $this->stepInfo('%-40s | %s', 'Port ID', 'Port name');
        // $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        // foreach ($ports as $port) {
        //     $this->stepInfo('%-40s | %s', $port->getId(), $port->getName());
        //     $this->cleanupPortIds[] = $port->getId();
        // }

        $this->step('List ports');
        $ports = $this->getService()->listPorts();
        $this->stepInfo('%-40s | %s', 'Port ID', 'Port name');
        $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        foreach ($ports as $port) {
            $this->stepInfo('%-40s | %s', $port->getId(), $port->getName());
        }

        $this->step('Get port');
        $port = $this->getService()->getPort($port->getId());
        $this->stepInfo('Port ID: ' . $port->getId());
        $this->stepInfo('Port Name: ' . $port->getName());

        $this->step('Update port');
        $port->update(array(
            'name'     => 'updated_test_port',
            'fixedIps' => array(
                array(
                    'subnetId'  => $subnet1->getId(),
                    'ipAddress' => '192.168.62.17'
                )
            )
        ));
    }

    public function teardown()
    {
        foreach ($this->cleanupPortIds as $portId) {
            $port = $this->getService()->getPort($portId);
            $port->delete();
        }

        foreach ($this->cleanupSubnetIds as $subnetId) {
            $subnet = $this->getService()->getSubnet($subnetId);
            $subnet->delete();
        }

        foreach ($this->cleanupNetworkIds as $networkId) {
            $network = $this->getService()->getNetwork($networkId);
            $network->delete();
        }
    }
}
