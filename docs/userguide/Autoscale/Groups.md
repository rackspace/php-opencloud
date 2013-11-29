#Auto Scale groups

## Intro

The scaling group is at the heart of an Auto Scale deployment. The scaling group specifies the basic elements of the Auto Scale configuration. It manages how many servers can participate in the scaling group. It also specifies information related to load balancers if your configuration uses a load balancer.

## Service setup

Nothing special here; you setup your client and service objects in the same way as every other resource:

```php
$service = $client->autoscaleService();
```

Please consult the [client doc](docs/userguide/Client.md) for more information about creating clients.

## List all groups

```php
$groups = $service->groupList();
```

Please consult the [iterator doc](docs/userguide/Iterators.md) for more information about iterators.

## Retrieve one group by ID

```php
$group = $service->group('605e13f6-1452-4588-b5da-ac6bb468c5bf');
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