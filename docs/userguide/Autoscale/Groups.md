#Autoscale groups

Groups acts as categories for your Autoscaling. Each scaling group has its own set of configuration properties which define its state and relationship with other resources. 

## Setup the service

Nothing special here; you setup your connection and service objects in the same way as every other resource:

```php
use OpenCloud\Rackspace;
use OpenCloud\Autoscale\Service;

$connection = new Rackspace(
    RACKSPACE_US,
    array(
        'username' => 'foo',
        'apiKey'   => 'bar'
    )
);

// Either use the convenient factory method
$service = $connection->autoscale('autoscale', 'DFW', 'publicURL');

// or manually instantiate
$service = new Service($connection, 'autoscale', 'DFW', 'publicURL');
```

## List all groups

```php
$groups = $service->groupList();

while ($group = $groups->next()) {
    // do something with the individual object
}
```

## Retrieve one group by ID

```php
$id = 'foobar';

$group = $service->group($id);
```

## Create a new group

You can create a new scaling group in two ways: either pass in a JSON string (easier option); or  instantiate a new object and manually set its properties (a lot more verbose)

```php

// Easy way:

$jsonString = <<<EOT
{"groupConfiguration":{"name":"New autoscale group","minEntities":5,"maxEntities":25,"cooldown":60},"launchConfiguration":{"type":"launch_server","args":{"server":{"flavorRef":3,"name":"webhead","imageRef":"0d589460-f177-4b0f-81c1-8ab8903ac7d8"},"loadBalancers":[{"loadBalancerId":2200,"port":8081}]}},"scalingPolicies":{"name":"scale up by 10","change":10,"cooldown":5,"type":"webhook"}}
EOT;

$group->create($jsonString);

// More granular (and verbose) way:

$object = new \stdClass;

// Set the config object for this autoscale group; contains all of properties
// which determine its behaviour
$object->groupConfiguration = new \stdClass;
$object->groupConfiguration->name = 'New autoscale group';
$object->groupConfiguration->minEntities = 5;
$object->groupConfiguration->maxEntities = 25;
$object->groupConfiguration->cooldown = 60;

// We need specify what is going to be launched. For now, we'll launch a new server
$object->launchConfiguration = new \stdClass;
$object->launchConfiguration->type = 'launch_server';

// To launch a new server, we need two `args` properties: `server` and `loadBalancer`
$server = new \stdClass;
$server->flavorRef = 3;
$server->name = 'webhead';
$server->imageRef = "0d589460-f177-4b0f-81c1-8ab8903ac7d8";

$loadBalancer = new \stdClass;
$loadBalancer->loadBalancerId = 2200;
$loadBalancer->port = 8081;

$object->launchConfiguration->args = new \stdClass;
$object->launchConfiguration->args->server = $server;
$object->launchConfiguration->args->loadBalancers = array($loadBalancer);

// Do we want particular scaling policies?
$policy = new \stdClass;
$policy->name = "scale up by 10";
$policy->change = 10;
$policy->cooldown = 5;
$policy->type = "webhook";

$object->scalingPolicies = array($policy);

$group->create($object);
```

## Delete an autoscale group
```php
$group->delete();
```

## Get the current state of the scaling group

```php
$group->getState();
```