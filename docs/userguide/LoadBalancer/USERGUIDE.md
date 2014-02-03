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

### List Nodes

### Add Nodes

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
