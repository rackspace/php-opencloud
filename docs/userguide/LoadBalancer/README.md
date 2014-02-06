# Load Balancers

A **load balancer** is a device that distributes incoming network traffic amongst
multiple back-end systems. These back-end systems are called the **nodes** of
the load balancer.

## Getting started

### 1. Instantiate a Rackspace client.

```php

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME'>,
    'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
));
```

### 2. Retrieve the server instances you want to add as nodes of the load balancer.

```php
$computeService = $client->computeService('cloudServersOpenStack', 'DFW');

$serverOne = $computeService->server('e836fc4e-056d-4447-a80e-fefcaa640216');
$serverTwo = $computeService->server('5399cd36-a23f-41a6-bdf7-20902aec0e74');
```

The example above uses two server instances that have already been created. It
retrieves the server instances using their IDs. See also: [creating server instances]().

### 3. Obtain a Load Balancer service object from the client.

This object will be used to first define the load balancer nodes and later create the load balancer itself.

```php
$loadBalancerService = $client->loadBalancerService('cloudLoadBalancers', 'DFW');
```

### 4. Define a load balancer node for each server.

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
```

In the example above, each node runs a service that listens on port 8080. Further, 
each node will start out as `ENABLED`, which means it will be ready to receive
network traffic from the load balancer as soon as it is created.

### 5. Create the load balancer with the two nodes.

```php
$loadBalancer->addVirtualIp('PUBLIC');
$loadBalancer->create(array(
    'name' => 'My smart load balancer',
    'port' => 80,
    'protocol' => 'HTTP',
    'nodes' => array($serverOneNode, $serverTwoNode)
));
```

In the example above, the load balancer will have a virtual IP address accessible 
from the public Internet. Also notice that the port the load balancer listens
on (80) does not need to match the ports of its nodes (8080).

## Next steps

Once you have created a load balancer, there is a lot you can do with it. See 
the [complete user guide for load balancers](USERGUIDE.md).