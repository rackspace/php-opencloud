# The Complete User Guide to Load Balancers

## Prerequisites

### Client
To use the load balancers service, you must first instantiate a `Rackspace`
client object.

```php
use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME'>,
    'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
));
```

### Load Balancer Service
All operations on load balancers are done via a load balancer service object.

```php
$loadBalancerService = $client->loadBalancerService('cloudLoadBalancers', 'DFW');
```

### Cloud Servers
Many of the examples in this document use two cloud servers as nodes for
the load balancer. The variables `$serverOne` and `$serverTwo` refer to these
two cloud servers.

## Load Balancers

A **load balancer** is a device that distributes incoming network traffic amongst
multiple back-end systems. These back-end systems are called the **nodes** of
the load balancer.

### Create Load Balancer

```php
$loadBalancer = $loadBalancerService->loadBalancer();

$serverOneNode = $loadBalancer->node();
$serverOneNode->address = $serverOne->addresses->private[0]->addr;
$serverOneNode->port = 8080;
$serverOneNode->condition = 'ENABLED';

$serverTwoNode = $loadBalancer->node();
$serverTwoNode->address = $serverTwo->addresses->private[0]->addr;
$serverTwoNode->port = 8080;
$serverTwoNode->condition = 'ENABLED';

$loadBalancer->addVirtualIp('PUBLIC');
$loadBalancer->create(array(
    'name' => 'My smart load balancer',
    'port' => 80,
    'protocol' => 'HTTP',
    'nodes' => array($serverOneNode, $serverTwoNode)
));
```

### List Load Balancer Details

You can retrieve a single load balancer's details by using its ID.

```php
$loadBalancer = $loadBalancerService->loadBalancer('254889');
var_dump($loadBalancer); // instance of OpenCloud\LoadBalancer\Resource\LoadBalancer
```

### List Load Balancers

You can retrieve a list of all your load balancers. An instance of `OpenCloud\Common\Collection\PaginatedIterator`
is returned.

```php
$loadBalancers = $loadBalancerService->loadBalancerList();
foreach ($loadBalancers as $loadBalancer) {
    var_dump($loadBalancer); // instance of OpenCloud\LoadBalancer\Resource\LoadBalancer
}
```

### Update Load Balancer Attributes

You can update one or more of the following load balancer attributes:
* `name`: The name of the load balancer
* `algorithm`: The algorithm used by the load balancer to distribute traffic amongst its nodes. See also: [Load balancing algorithms](#algorithms).
* `protocol`: The network protocol used by traffic coming in to the load balancer. See also: [protocols](#protocols).
* `port`: The network port on which the load balancer listens for incoming traffic.
* TODO: More attributes `halfClosed`, `timeout`, `httpsRedirect`

#### Updating just the name of the load balancer
```php
$loadBalancer->update(array(
    'name' => 'New name'
));
```

#### Updating the name and algorithm of the load balancer
```php
$loadBalancer->update(array(
    'name' => 'New name',
    'algorithm' => 'ROUND_ROBIN'
));
```

#### Updating the protocol and the port of the load balancer
```php
$loadBalancer->update(array(
    'protocol' => 'HTTPS',
    'port' => 443
));
```

### Remove Load Balancer

When you no longer have a need for the load balancer, you can remove it.

```php
$loadBalancer->delete(); 
```

## Nodes

A **node** is a backend device that provides a service on specified IP and port. An example of a load balancer node might be a web server serving HTTP traffic on port 8080.

A load balancer typically has multiple nodes attached to it so it can distribute incoming network traffic amongst them.

### List Nodes

You can list the nodes attached to a load balancer. An instance of `OpenCloud\Common\Collection\PaginatedIterator`
is returned.

```php
$nodes = $loadBalancer->listNodes();
foreach ($nodes as $node) {
	var_dump($node); // instance of OpenCloud\LoadBalancer\Resource\Node}
```

### Add Nodes

You can attach additional nodes to a load balancer. Assume `$loadBalancer` already has two nodes attached to it - `$serverOne` and `$serverTwo` - and you want to attach a third node to it, say `$serverThree`, which provides a service on port 8080.

```php
$loadBalancer->addNode(
    $serverThree->addresses->private[0]->addr,
    8080
);
```

The `addNode` method accepts three more optional arguments, in addition to the two required arguments shown above.

#### Arguments to `addNode`

| Position | Description | Required? | Default value |
| ----------- | --------------- | -------------- | ----------------- |
|  1           | IP address of node | Yes | - |
|  2           | Port used by node's service | Yes | - |
|  3           | Starting condition of node:
* `ENABLED` – Node is ready to receive traffic from the load balancer
* `DISABLED` – Node should not receive traffic from the load balancer
* `DRAINING` – Node should process any traffic it is already receiving but should not receive any further traffic from the load balancer | No | `ENABLED` |
|  4           | Type of node to add:
* `PRIMARY – Nodes defined as PRIMARY are in the normal rotation to receive traffic from the load balancer.
* SECONDARY – Nodes defined as SECONDARY are only in the rotation to receive traffic from the load balancer when all the primary nodes fail.  | No | `PRIMARY` |
|  5           | Weight between 1 and 100 given to node when distributing traffic using either the `WEIGHTED_ROUND_ROBIN` or the `WEIGHTED_LEAST_CONNECTIONS` load balancing algorithm. | No | 1 |

### Modify Nodes

### Remove Nodes

### View Node Service Events

## Virtual IPs

### List Virtual IPs

## Algorithms

### List Load Balancing Algorithms

## Protocols

### List Load Balancing Protocols

## Sessions

### Manage Session Persistence

## Connections

###

## Error Page

## Allowed Domains

## Access Lists

## Content Caching

## SSL Termination

## Metadata

## Monitors

## Statistics

## Usage Reports
