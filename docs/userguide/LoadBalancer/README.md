# Load Balancers

A load balancer is a device that distributes incoming network traffic amongst
multiple back-end systems. These back-end systems are called the **nodes** of
the load balancer.

## Getting started

1. Instantiate a Rackspace client.

   ```php

   use OpenCloud\Rackspace;

   $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
                               'username' => 'YOUR RACKSPACE CLOUD ACCOUNT USERNAME',
                               'apiKey' =>   'YOUR RACKSPACE CLOUD ACCOUNT API KEY'
                          ));
   ```

2. Retrieve the server instances you wish to add as nodes of the load balancer.
In the example below I assume two server instances have already been created,
so I retrieve them using their IDs. You can learn how to create server instances
over [here]().

   ```php
   $computeService = $client->computeService('cloudServersOpenStack', 'DFW');
   $serverOne = $computeService->server('e836fc4e-056d-4447-a80e-fefcaa640216');
   $serverTwo = $computeService->server('5399cd36-a23f-41a6-bdf7-20902aec0e74');
   ```

3. Obtain a Load Balancer service object from the client. This object will be used
to first define the load balancer nodes and then later create the load balancer itself.

```php
$loadBalancerService = $client->loadBalancerService('cloudLoadBalancers', 'DFW');
```

4. Define a load balancer node for each server. In this example, each
   node runs a service that listens on port 8080. Further, we want each node
   to be enabled (i.e. ready to serve traffic) when the load balancer is created.

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

5. Create the load balancer with the two nodes. In this example, the load
   balancer has a virtual IP address that is accessible from the public Internet.
   Also note that the port that the load balancer listens on does not need to
   match the ports of its nodes, as shown in this example.

```php
$loadBalancer->addVirtualIp('PUBLIC');
$loadBalancer->create(array(
    'name' => 'My smart load balancer',
    'port' => 80,
    'protocol' => 'HTTP',
    'nodes' => array(
        $serverOneNode,
        $serverTwoNode
        )
    ));
```

## Next steps
* Once you have created load balancers, you can add more nodes to them,
  delete them, etc. You can learn more about such operations over [here]().
* By default a load balancer randomly distributes traffic amongst its nodes.
  There are other distribution algorithms available as well. You can learn about
  these algorithms over [here]().
* Load balancers have many optional features. For instance, they can monitor
  the health of their nodes and decide whether to send traffic to them or not.
  You can learn about these features overs [here]().
