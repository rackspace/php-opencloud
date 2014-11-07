# Complete User Guide for the Networking Service

Networking is a service that you can use to create virtual networks and attach cloud devices
such as servers to these networks.

This user guide will introduce you the entities in the Networking service &mdash; networks, subnets, and ports &mdash; as well as show you how to create and manage these entities.

## Table of Contents
  * [Concepts](#concepts)
  * [Prerequisites](#prerequisites)
    * [Client](#client)
    * [Networking service](#networking-service)
  * [Networks](#networks)
    * [Create a network](#create-a-network)
    * [Create multiple networks](#create-multiple-networks)
    * [List networks](#list-networks)
    * [Get network](#get-network)
    * [Update network](#update-network)
    * [Delete network](#delete-network)
  * [Subnets](#subnets)
    * [Create a subnet](#create-a-subnet)
    * [Create multiple subnets](#create-multiple-subnets)
    * [List subnets](#list-subnets)
    * [Get subnet](#get-subnet)
    * [Update subnet](#update-subnet)
    * [Delete subnet](#delete-subnet)
  * [Ports](#ports)
    * [Create a port](#create-a-port)
    * [Create multiple ports](#create-multiple-ports)
    * [List ports](#list-ports)
    * [Get port](#get-port)
    * [Update port](#update-port)
    * [Delete port](#delete-port)

## Concepts

To use the Networking service effectively, you should understand the following key concepts:

* **Network**: A network is an isolated virtual layer-2 broadcast domain that is typically reserved for the tenant who created it unless you configure the network to be shared. The network is the main entity in the Networking service. Ports and subnets are always associated with a network.

* **Subnet**: A subnet represents an IP address block that can be used to assign IP addresses to virtual instances (such as servers created using the Compute service). Each subnet must have a CIDR and must be associated with a network.

* **Port**: A port represents a virtual switch port on a logical network switch. Virtual instances (such as servers created using the Compute service) attach their interfaces into ports. The port also defines the MAC address and the IP address(es) to be assigned to the interfaces plugged into them. When IP addresses are associated to a port, this also implies the port is associated with a subet, as the IP address is taken from the allocation pool for a specific subnet.

## Prerequisites

### Client
To use the Networking service, you must first instantiate a `OpenStack` or `Rackspace` client object.

* If you are working with an OpenStack cloud, instantiate an `OpenCloud\OpenStack` client as follows:

    ```php
    use OpenCloud\OpenStack;

    $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
        'username' => '<YOUR OPENSTACK CLOUD ACCOUNT USERNAME>',
        'password' => '<YOUR OPENSTACK CLOUD ACCOUNT PASSWORD>'
    ));
    ```

* If you are working with the Rackspace cloud, instantiate a `OpenCloud\Rackspace` client as follows:

    ```php
    use OpenCloud\Rackspace;

    $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
    ));
    ```

### Networking service

All Networking operations are done via a _networking service object_. To 
instantiate this object, call the `networkingService` method on the `$client`
object. This method takes two arguments:

| Position | Description | Data type | Required? | Default value | Example value |
| -------- | ----------- | ----------| --------- | ------------- | ------------- |
|  1       | Name of the service, as it appears in the service catalog | String | No | `null`; automatically determined when possible | `cloudNetworks` |
|  2       | Cloud region | String | Yes | - | `DFW` |


```php
$region = '<CLOUD REGION NAME>';
$networkingService = $client->networkingService(null, $region);
```

Any networks, subnets, and ports created with this `$networkingService` instance will
be stored in the cloud region specified by `$region`.

## Networks

A network is an isolated virtual layer-2 broadcast domain that is typically reserved for the tenant who created it unless you configure the network to be shared. The network is the main entity in the Networking service. Ports and subnets are always associated with a network.

### Create a network

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | Human-readable name for the network. Might not be unique. | String | No | `null` | `My private backend network` |
| `adminStateUp` | The administrative state of network. If `false` (down), the network does not forward packets. | Boolean | No | `true` | `true` |
| `shared` | Specifies whether the network resource can be accessed by any tenant or not. | Boolean | No | `false` | `false` |
| `tenantId` | Owner of network. Only admin users can specify a tenant ID other than their own. | String | No | Same as tenant creating the network | `123456` |

You can create a network as shown in the following example:

```php
$network = $networkingService->createNetwork(array(
    'name' => 'My private backend network'
));
/** @var $network OpenCloud\Networking\Resource\Network **/
```

[ [Get the executable PHP script for this example](/samples/Networking/create-network.php) ]

### Create multiple networks

This operation takes one parameter, an indexed array. Each element of this array must
be an associative array with the keys shown in [the table above](#create-a-network).

You can create multiple networks as shown in the following example:

```php
$networks = $networkingService->createNetworks(array(
    array(
        'name' => 'My private backend network #1'
    ),
    array(
        'name' => 'My private backend network #2'
    )
));

foreach ($networks as $network) {
    /** @var $network OpenCloud\Networking\Resource\Network **/
}
```

[ [Get the executable PHP script for this example](/samples/Networking/create-networks.php) ]

### List networks

You can list all the networks to which you have access as shown in the following example:

```php
$networks = $networkingService->listNetworks();
foreach ($networks as $network) {
    /** @var $network OpenCloud\Networking\Resource\Network **/
}
```

[ [Get the executable PHP script for this example](/samples/Networking/list-networks.php) ]

### Get network

You can retrieve a specific network by using that network's ID, as shown in the following example:

```php
$network = $networkingService->getNetwork('eb60583c-57ea-41b9-8d5c-8fab2d22224c');
/** @var $network OpenCloud\Networking\Resource\Network **/
```

[ [Get the executable PHP script for this example](/samples/Networking/get-network.php) ]

### Update network

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | Human-readable name for the network. Might not be unique. | String | No | `null` | `My updated private backend network` |
| `adminStateUp` | The administrative state of network. If `false` (down), the network does not forward packets. | Boolean | No | `true` | `true` |
| `shared` | Specifies whether the network resource can be accessed by any tenant or not. | Boolean | No | `false` | `false` |

You can update a network as shown in the following example:

```php
$network->update(array(
    'name' => 'My updated private backend network'
));
```

[ [Get the executable PHP script for this example](/samples/Networking/update-network.php) ]

### Delete network

You can delete a network as shown in the following example:

```php
$network->delete();
```

[ [Get the executable PHP script for this example](/samples/Networking/delete-network.php) ]

## Subnets

A subnet represents an IP address block that can be used to assign IP addresses to virtual instances (such as servers created using the Compute service). Each subnet must have a CIDR and must be associated with a network.

### Create a subnet

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `networkId` | Network this subnet is associated with | String | Yes | - | `eb60583c-57ea-41b9-8d5c-8fab2d22224c` |
| `ipVersion` | IP version | Integer (`4` or `6`) | Yes | - | `4` |
| `cidr` | CIDR representing IP range for this subnet | String (CIDR) | Yes | - | `192.168.199.0/25` |
| `name` | Human-readable name for the subnet. Might not be unique. | String | No | `null` | `My subnet` |
| `gatewayIp` | IP address of the default gateway used by devices on this subnet | String (IP address) | No | First IP address in CIDR | `192.168.199.128` |
| `dnsNameservers` | DNS nameservers used by hosts in this subnet | Indexed array of strings | No | Empty array | `array('4.4.4.4', '8.8.8.8')` |
| `allocationPools` | Sub-ranges of CIDR available for dynamic allocation to ports | Indexed array of associative arrays | No | Every IP address in CIDR, excluding gateway IP address if configured | `array(array('start' => '192.168.199.2', 'end' => '192.168.199.127'))` |
| `hostRoutes` | Routes that should be used by devices with IPs from this subnet (not including local subnet route) | Indexed array of associative arrays | No | Empty array | `array(array('destination' => '1.1.1.0/24', 'nexthop' => '192.168.19.20'))` |
| `enableDhcp` | Specifies whether DHCP is enabled for this subnet or not | Boolean | No | `true` | `false` |
| `tenantId` | Owner of subnet. Only admin users can specify a tenant ID other than their own. | String | No | Same as tenant creating the subnet | `123456` |

You can create a subnet as shown in the following example:

```php
$subnet = $networkingService->createSubnet(array(
    'name' => 'My subnet',
    'networkId' => 'eb60583c-57ea-41b9-8d5c-8fab2d22224c',
    'ipVersion' => 4,
    'cidr' => '192.168.199.0/25'
));
/** @var $subnet OpenCloud\Networking\Resource\Subnet **/
```

[ [Get the executable PHP script for this example](/samples/Networking/create-subnet.php) ]

### Create multiple subnets

This operation takes one parameter, an indexed array. Each element of this array must
be an associative array with the keys shown in [the table above](#create-a-subnet).

You can create multiple subnets as shown in the following example:

```php
$subnets = $networkingService->createSubnets(array(
    array(
        'name' => 'My subnet #1'
    ),
    array(
        'name' => 'My subnet #2'
    )
));

foreach ($subnets as $subnet) {
    /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
}
```

[ [Get the executable PHP script for this example](/samples/Networking/create-subnets.php) ]

### List subnets

You can list all the subnets to which you have access as shown in the following example:

```php
$subnets = $networkingService->listSubnets();
foreach ($subnets as $subnet) {
    /** @var $subnet OpenCloud\Networking\Resource\Subnet **/
}
```

[ [Get the executable PHP script for this example](/samples/Networking/list-subnets.php) ]

### Get subnet

You can retrieve a specific subnet by using that subnet's ID, as shown in the following example:

```php
$subnet = $networkingService->getSubnet('d3f15879-fb11-49bd-a30b-7704fb98ab1e');
/** @var $subnet OpenCloud\Networking\Resource\Subnet **/
```

[ [Get the executable PHP script for this example](/samples/Networking/get-subnet.php) ]

### Update subnet

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | Human-readable name for the subnet. Might not be unique. | String | No | `null` | `My updated subnet` |
| `gatewayIp` | IP address of the default gateway used by devices on this subnet | String (IP address) | No | First IP address in CIDR | `192.168.62.155` |
| `dnsNameservers` | DNS nameservers used by hosts in this subnet | Indexed array of strings | No | Empty array | `array('4.4.4.4', '8.8.8.8')` |
| `hostRoutes` | Routes that should be used by devices with IPs from this subnet (not including local subnet route) | Indexed array of associative arrays | No | Empty array | `array(array('destination' => '1.1.1.0/24', 'nexthop' => '192.168.17.19'))` |
| `enableDhcp` | Specifies whether DHCP is enabled for this subnet or not | Boolean | No | `true` | `false` |

You can update a subnet as shown in the following example:

```php
$subnet->update(array(
    'name' => 'My updated subnet',
    'hostRoutes' => array(
        array(
            'destination' => '1.1.1.0/24',
            'nexthop'     => '192.168.17.19'
        )
    ),
    'gatewayIp' => '192.168.62.155'
));
```

[ [Get the executable PHP script for this example](/samples/Networking/update-subnet.php) ]

### Delete subnet

You can delete a subnet as shown in the following example:

```php
$subnet->delete();
```

[ [Get the executable PHP script for this example](/samples/Networking/delete-subnet.php) ]

## Ports

### Create a port

### Create multiple ports

### List ports

### Get port

### Update port

### Delete port